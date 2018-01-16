<html>
<head/>
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

if (NULL != $usetx) {
    $json = `${_BLOCKCHAIND} gettransaction ${usetx}`;
} else {
    if (NULL != $useheight) {
        $json = `${_BLOCKCHAIND} getblockbynumber ${useheight} true     `;
    } else {
        if (NULL == $useblock) {
            $useblock = `${_BLOCKCHAIND} getbestblockhash`;
        }
        $json = `${_BLOCKCHAIND} getblock ${useblock}  true  `;
    }
}
$input = json_decode($json);

#printf($json);
?>


<table>
    <?php
    echo html_table_write($input);
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
    if (omitLabel($label)) return;
    $typ = gettype($inputObject);
    $skip = false;
    $accum = "";
    switch ($typ) {
        case "array":
            foreach ($inputObject as $val) {
                $accum .= "<td> " . html_table_write($val, $label) . "</td>";
            }
            break;
        case "object":
            foreach ($inputObject as $key => $val) {
                $accum .= "<tr><th>$key</th>" . html_table_write($val, $key) . "</tr>";
            }
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
            $accum .= "<td>" . linkTo($label, $inputObject) . "</td>";
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
        case "previousblockhash":
        case "nextblockhash":
        case "blockhash":
        case "hash":
            $link = "<a href=" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?block=$val title=$val>" . substr($val, 0, 8) . "</a>";
            break;
        case "addresses":
            $link = "<a href=" . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . "?address=" . $val . ">$val</a>";
            break;

        default:
            break;
    }
    return $link;
}
function matchSuffix($subject, $suffix)
{
    return substr($subject, -strlen($suffix)) == $suffix ? "true" : "false";
}
