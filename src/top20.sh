#!/bin/bash
jq "[limit(20;.rows|sort_by(.value)|reverse[])]|from_entries" < <(curl "@@COUCHPREFIX@@/@@SYM@@_blocks/_design/address/_view/value?group=true&reduce=true" )
