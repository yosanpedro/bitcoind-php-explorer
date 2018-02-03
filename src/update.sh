#!/bin/bash

for (( i = $(@@BITCOIND@@  getblockcount  ) ; i != 0 ; i-- )); do curl -f -XPUT  @@COUCHPREFIX@@/@@SYM@@_blocks/$i -d@<(@@BITCOIND@@  getblockbynumber ${i} true )||break ;  done
