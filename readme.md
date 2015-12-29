## Test task

### Environment
Ubuntu 14.04  
php 5.5  
mysql 5.5  

### Description
For implementation test task there was user [Lumen](https://lumen.laravel.com) framework.

### Usage
make clone of this repository:
```
git clone https://github.com/Sharykhin/pipedrive-test.git
```
run vagrant
```
cd pipedrive-test
vagrant up
```

If you don't have Vagrant, go to the [Vagrant site](https://www.vagrantup.com/) and install it

### testing

Open browser http://192.168.55.55/

Requests:  
GET http://192.168.55.55/api/v1/test

GET http://192.168.55.55/api/v1/organizations  
POST http://192.168.55.55/api/v1/organizations   
DELETE http://192.168.55.55/api/v1/organizations  
