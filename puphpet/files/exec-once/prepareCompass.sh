#!/bin/bash

set -e
# nodejs stuff
curl -sL https://deb.nodesource.com/setup | sudo bash - &&
sudo apt-get -q -y install build-essential nodejs figlet


npm config set prefix ~/npm
sudo mkdir -p ~/npm
echo "export-PATHS"
echo "export PATH=\"$PATH:$HOME/npm/bin\"" >> ~/.bashrc
echo "export NODE_PATH=\"$NODE_PATH:$HOME/npm/lib/node_modules\"" >> ~/.bashrc
export PATH="$PATH:$HOME/npm/bin"
export NODE_PATH="$NODE_PATH:$HOME/npm/lib/node_modules"



sudo rm -rf /var/www/node_modules
cd /var/www/
cp /var/www/smoothieme/package.json /var/www/package.json &&





## remove defaults
sudo rm -rf /var/www/html
sudo rm -rf /var/www/default
## add zf tool
sudo ln -s /var/www/smoothieme/bin/zf.sh /usr/local/bin/zf
#chown vagrant:vagrant /usr/local/bin/zf
sudo gem install sass compass

#compass create -r bootstrap-sass --using bootstrap  --javascript-dir js --css-dir css --fonts-dir fonts --sass-dir sass --images-dir img
sudo echo "cd /var/www/smoothieme">> /home/vagrant/.bashrc

sudo cd /var/www/smoothieme
composer update

set +e