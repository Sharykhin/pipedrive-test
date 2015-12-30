## Test task

### Environment
Ubuntu 14.04  
php 5.5  
mysql 5.5  

### Description
There was used [Lumen](https://lumen.laravel.com) framework.

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

For running tests use the following command:
```
vagrant ssh -c 'cd /var/www && ./vendor/bin/phpunit tests/ --verbose'
```

Requests:  

GET http://192.168.55.55/api/v1/test

Get all organizations
```
curl -XGET http://192.168.55.55/api/v1/organizations --header "Content-Type: application/json"
```
Create an organization (on pipedrive as well)
```
curl -XPOST http://192.168.55.55/api/v1/organizations --header "Content-Type: application/json" --header "Accept: application/json" -d '{"name":"Banana"}'
```
Get an organization by id
```
curl -XGET http://192.168.55.55/api/v1/organizations/1 --header "Content-Type: application/json"
```

Delete an organization by id
```
curl -XDELETE http://192.168.55.55/api/v1/organizations/1 --header "Content-Type: application/json"
```

Delete all organizations
```
curl -XGET http://192.168.55.55/api/v1/organizations --header "Content-Type: application/json"
```

#### Create relationship:
```
curl -XPOST http://192.168.55.55/api/v1/organizationRelationships --header "Content-Type: application/json" --header "Accept: application/json" -d '{"org_name":"Paradise Island", "daughters":[{"org_name":"Banana tree", "daughters":[{"org_name":"Yellow Banana"},{"org_name":"Brown Banana"},{"org_name":"Green Banana"}]},{"org_name":"Nestle"}]}'
```

#### Get all relationships by organization id
```
curl -XGET http://192.168.55.55/api/v1/organizationRelationships?org_id=1 --header "Content-Type: application/json"
```
#### Remove all data
```
curl -XGET http://192.168.55.55/api/v1/clearAll --header "Content-Type: application/json"
```
