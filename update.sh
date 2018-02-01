#!/bin/bash

for (( i = $(~/extensivecoind  getblockcount  ) ; i != 0 ; i-- )); do curl -f -XPUT  http://127.0.0.1:5984/extn_blocks/$i -d@<(~/stackbitd  getblockbynumber ${i} true )||break ;  done
