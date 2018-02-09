#!/bin/bash
set -x
#@@BITCOIND@@
#@@BLOCKCHAINNAME@@
#@@COUCHPREFIX@@
#@@SYM@@
BITCOIND=${BITCOIND_:="~/bitcoind"}
BLOCKCHAINNAME=${BLOCKCHAINNAME_:="bitcoin"}
COUCHPREFIX=${COUCHPREFIX_:="http://127.0.0.1:5984"}
SYM=${SYM_:="btc"}

srcfiles=src/
tokens=(BITCOIND BLOCKCHAINNAME COUCHPREFIX SYM)
mkdir -p target
cp src/* target

for i in ${tokens[*]}; do
sed -i 's,@@'${i}'@@,'$(eval echo \$${i})',g' target/*
done

pushd target
chmod a+x *.sh
bash ./init.sh
bash ./update.sh
popd
set +x
