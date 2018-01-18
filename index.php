<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>Document</title><style type='text/css'>
      tbody {
                        background-color: #c1c2ed;
                     }
      .hdr {
            background-color: #FFEB3B;
            box-shadow: inset -2px 2px 5px grey;
        }
        .data{
            background-color: gainsboro ;
            box-shadow: inset -2px 2px 5px grey;
        }
    </style>
</head>
<body>
<?php
$_BLOCKCHAIND = "~/stackbitd";
$qry = [];
parse_str($_SERVER['QUERY_STRING'], $qry);

//var_dump($qry);

if (isset($qry["block"])) {
    $useblock = $qry["block"];
}
if (isset($qry["height"])) {
    $useheight = $qry["height"];
}
if (isset($qry["tx"])) {
    $usetx = $qry["tx"];
}

if (isset($qry["address"])) {
    $useaddress = $qry["address"];
}

if(NULL!= $useaddress){
$amt=json_decode( `curl "http://localhost:5984/sbit_blocks/_design/address/_view/value?reduce=true&key=%22$useaddress%22" `);
$cnt=json_decode( `curl "http://localhost:5984/sbit_blocks/_design/address/_view/counter?group=true&reduce=true&key=%22$useaddress%22" `);
//$first=`jq    .rows[].value < <(curl "http://localhost:5984/sbit_blocks/_design/address/_view/counter?group=true&reduce=true&key=%22$useaddress%22")`;
 echo "the address ${useaddress} has obtained/retained roughly ". ${amt}->rows[0]->value ." in ". ${cnt}->rows[0]->value ." transactions";
 exit(0);

}
else if (NULL != $usetx) {
    $json = `${_BLOCKCHAIND} gettransaction ${usetx}`;
} else {
    if (NULL != $useheight) {
        $json = `${_BLOCKCHAIND} getblockbynumber ${useheight} true     `;
    } else {
        if (NULL == $useblock) {
            $useblock = `${_BLOCKCHAIND} getbestblockhash  `;
        }
        $json = `${_BLOCKCHAIND} getblock ${useblock}  true  `;
    }
}
$input = json_decode($json);

#printf($json);
?>

<table>
    <?php
    echo html_table_write($input, "toplevel");
    ?>
</table>
<?php
function omitLabel($label)
{
    switch ($label) {
        case "n":
        case "asm":
        case "hex":
            return true;
        default :
            break;
    }
    return false;
}

/**
 * @param $inputObject
 */
function html_table_write($inputObject, $label)
{
    if (omitLabel($label)) {
        return "";
    }
    $typ = gettype($inputObject);
    $skip = false;
    $accum = "";
    switch ($typ) {
        case "array":
            $accum .= "<td><table>";
            foreach ($inputObject as $val) {
                $accum .= "<tr>" . html_table_write($val, $label) . "</tr> ";
            }
            $accum .= "</table></td>";

            break;
        case "object":
            $accum .= "
<td style='border: thin black;'><table>";
            foreach ($inputObject as $key => $val) {
                $accum .= "
<tr><th class='hdr'>$key</th>" . html_table_write($val, $key) . "</tr>";
            }
            $accum .= "</table></td>";
            break;
        case "resource":
        case "NULL":
        case "unknown type":
            error_log("punting on " . json_encode($inputObject));
        case     "boolean":
        case     "integer":
        case     "double" :
        case "string":
        default:
            $accum .= "<td class='data'>" . linkTo($label, $inputObject) . "</td>";
            break;
    }
    return $accum;

}

function linkTo($label, $val)
{

    $link = $val;
    switch ($label) {
        case "height":
            $link = "<a href=" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?height=" . $val . ">$val</a>";
            break;
        case  "tx":
        case "txid":
            $link = "<a href=" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?tx=$val title=$val>" . substr($val, 0, 8) . "</a>";
            break;
        case "proofhash":
        case "merkleroot":
        case "signature":
            $link = "<span title=$val>" . substr($val, 0, 8) . "...</span>";
            break;
        case "previousblockhash":
        case "nextblockhash":
        case "blockhash":
        case "hash":
            $link = "<a href=" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?block=$val title=$val>" . substr($val, 0, 8) . "</a>";
            break;
        case "addresses":
            $link = "<a href=" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?address=" . $val . ">$val</a>";
            break;
        case "time":
            $link = gmdate("Y-m-d\TH:i:s\Z", $val);
        default:
            break;
    }
    return $link;
}

function matchSuffix($subject, $suffix)
{
    return substr($subject, -strlen($suffix)) == $suffix ? "true" : "false";
}
