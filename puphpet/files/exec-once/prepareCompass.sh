#!/bin/bash

# nodejs stuff
#curl -sL https://deb.nodesource.com/setup | sudo bash -
#sudo apt-get install build-essential nodejs npm &&
#npm install -g gulp
#npm install gulp gulp-livereload  gulp-run path --no-bin-links --save-dev
#

# isntall latest ruby
sudo apt-get update
sudo apt-get upgrade -y -q


## remove defaults
rm -rf /var/www/html
rm -rf /var/www/default
## add zf tool
sudo ln -s /var/www/bin/zf.sh /usr/local/bin/zf
#chown vagrant:vagrant /usr/local/bin/zf
sudo gem install sass compass bootstrap-sass compass-validator guard guard-compass guard-sass guard-livereload

#compass create -r bootstrap-sass --using bootstrap  --javascript-dir js --css-dir css --fonts-dir fonts --sass-dir sass --images-dir img
echo "cd /var/www">> /home/vagrant/.bashrc

cd /var/www
composer update