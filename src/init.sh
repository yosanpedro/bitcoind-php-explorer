#!/bin/bash

curl -XPUT '@@COUCHPREFIX@@/@@SYM@@_blocks'

curl -XPUT '@@COUCHPREFIX@@/@@SYM@@_blocks/_design/address' -d '{ "_id": "_design/address", "views": { "value": { "reduce": "_sum", "map": "function (doc) { doc.tx.forEach(function (tx) {  tx.vout.forEach(function (vout) {   var ammt = vout.value;   if(vout.value && vout.value!=0&& vout.scriptPubKey  && vout.scriptPubKey.addresses  )   vout.scriptPubKey.addresses.forEach(function (addr) {    emit(addr, vout.value)   });  }) })}" }, "counter": { "map": " function (doc) { doc.tx.forEach(function (tx) {  tx.vout.forEach(function (vout) {   var ammt = vout.value;   if(vout.value && vout.value!=0&& vout.scriptPubKey  && vout.scriptPubKey.addresses  )   vout.scriptPubKey.addresses.forEach(function (addr) {    emit(addr, tx.txid)   });  }) })}", "reduce": "_count" } }, "language": "javascript" }'

curl -XPUT '@@COUCHPREFIX@@/@@SYM@@_blocks/_design/txid' -d '{ "_id": "_design/txid",    "views": { "tx": { "map": "function (doc) {  for (var  i  in doc.tx)     var tx=doc.tx[i]     emit(tx.txid,tx);}" } }, "language": "javascript" }'
