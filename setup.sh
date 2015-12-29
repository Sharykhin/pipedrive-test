echo "Provisioning virtual machine..."
sudo apt-get -y update

sudo apt-get install software-properties-common

echo "Installing vim"
sudo apt-get -y install vim

echo "Installing php5"
sudo apt-get install python-software-properties build-essential -y > /dev/null
sudo add-apt-repository ppa:ondrej/php5 -y > /dev/null
sudo apt-get update > /dev/null
sudo apt-get install php5-common php5-dev php5-cli php5-fpm -y > /dev/null

echo "Installing PHP extensions"
sudo apt-get install curl php5-curl php5-gd php5-mcrypt php5-mysql -y > /dev/null

echo "Installing nginx"
sudo apt-get install nginx -y > /dev/null

echo "Configuring Nginx"
sudo  cp /var/www/nginx_vhost /etc/nginx/sites-available/nginx_vhost > /dev/null

sudo  ln -s /etc/nginx/sites-available/nginx_vhost /etc/nginx/sites-enabled/

sudo rm -rf /etc/nginx/sites-available/default

sudo service nginx restart > /dev/null

echo "Installing Git"
sudo apt-get install git -y > /dev/null

echo "Installing Composer"
curl -sS https://getcomposer.org/installer | php
chmod 777 composer.phar
sudo mv composer.phar /usr/local/bin/composer

echo "Installing MySQL"
sudo apt-get install debconf-utils -y > /dev/null

sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password pass4root"
    
sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password pass4root"

sudo apt-get -y install mysql-server > /dev/null

echo "Setting databases"
mysqladmin -uroot -ppass4root create pipedrive
mysqladmin -uroot -ppass4root create pipedrive_test

echo "Instaling backend"
cd /var/www/
composer install
cp .env.example .env
php artisan migrate --force