

This is a blockchain browser which wraps json output in php and html tables.  

this is adequate to use as a blockexplorer to revie premines and see relevant transactions on 
bitcoin lineage 

 
prerequisites:

 * star the project 
 * fork the project 

ubuntu in anger:  
 `sudo apt-get install php-cli jq coreutils curl couchdb`

in bash:

`bash -li`

customize and run the generator (named armchair.sh)

```bash
BITCOIND="~/bitcoind"                 \\
BLOCKCHAINNAME="bitcoin"              \\
COUCHPREFIX="http://127.0.0.1:5984"   \\       
SYM="btc"                             \\
./armchair.sh
```

test:

```bash 
pushd target
php -S 0:8080
```

in a perfect world,  http://localhost:8080 may now look like:

![image](https://user-images.githubusercontent.com/73514/35766333-c035f0d2-0908-11e8-9795-4031d1d3f145.png)

you should expect about equal amount of time importing blocks from your blockchain into couchdb, following ( a long 
time after) the first attempts at viewing and the initial view creations for 'top 20 users' and 'total issued'


once this initial view generation is out of the way the explorer should be able to withstand a decent amount of test 
traffic on amazon t2 nano node.

'update.sh' will run on a naive incoming request (no url query params) and updates to the views are on demand, and 
reasonably responsive.  couchdb 2.x shards view generation so use that if it's important and you have cores to spare.

when done these links  should look similar to 
![image](https://user-images.githubusercontent.com/73514/35766366-9f63f6d2-0909-11e8-91a4-c9ce352895e2.png)
 
 and 
 
![image](https://user-images.githubusercontent.com/73514/35766371-c61f7210-0909-11e8-8068-d6a0e5c96334.png)



If you find this project is educational, informative, or profittable, please send bitcoin to my favorite indonesian family at 
bitcoin:12AKjrqMAptqgibC7krSpSFpY2qTDTV9pY ![image](https://user-images.githubusercontent.com/73514/35766583-be9af1d2-090d-11e8-857c-9b335ef51c09.png)

Thanks!

