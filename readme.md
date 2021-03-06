## Test task

### Environment
Ubuntu 14.04  
php 5.5  
mysql 5.5  

### Description
There was used [Lumen](https://lumen.laravel.com) framework.

### Usage
Step one: make clone of this repository:
```
git clone https://github.com/Sharykhin/pipedrive-test.git
```
Step two: run vagrant
```
cd pipedrive-test
vagrant up
```

If you don't have Vagrant, go to the [Vagrant site](https://www.vagrantup.com/) and install it

### Testing

For running tests use the following command:
```
vagrant ssh -c 'cd /var/www && ./vendor/bin/phpunit tests/ --verbose'
```

### API Requests:  

test: http://192.168.55.55/api/v1/test

**Get all organizations**
```
GET /api/v1/organizations
```
Response
```
{
  "success": true,
  "data": [
    {
      "id": "1",
      "name": "Organization Name",
      "created_at": "2015-12-30 13:19:00",
      "updated_at": "2015-12-30 13:19:00"
    }
  ],
  "error": null
}
```
Example
```
curl -XGET http://192.168.55.55/api/v1/organizations --header "Content-Type: application/json"
```
**Create an organization (on pipedrive as well)**
```
POST /api/v1/organizations
```
Input
```
{
    "name":"Organization Name"        
}
```

Response
```
{
  "success": true,
  "data": {
    "name": "Organization Name",
    "updated_at": "2015-12-30 13:19:00",
    "created_at": "2015-12-30 13:19:00",
    "id": 1
  },
  "error": null
}
```

Example
```
curl -XPOST http://192.168.55.55/api/v1/organizations --header "Content-Type: application/json" --header "Accept: application/json" -d '{"name":"Banana"}'
```

**Get an organization by id**
```
GET /api/v1/organizations/:id
```

Response
```
{
  "success": true,
  "data": {
    "id": "1",
    "name": "Organization Name",
    "created_at": "2015-12-30 13:19:00",
    "updated_at": "2015-12-30 13:19:00"
  },
  "error": null
}
```

Example
```
curl -XGET http://192.168.55.55/api/v1/organizations/1 --header "Content-Type: application/json"
```

**Update an organization by id**
```
PUT /api/v1/organizations/:id
```

Input
```
{
    "name":"Organization Name Changed"        
}
```

Response
```
{
  "success": true,
  "data": {
    "id": "1",
    "name": "Organization Name Changed",
    "created_at": "2015-12-30 13:19:00",
    "updated_at": "2015-12-30 13:23:45"
  },
  "error": null
}
```

Example
```
curl -XPUT http://192.168.55.55/api/v1/organizations --header "Content-Type: application/json" --header "Accept: application/json" -d '{"name":"Banana Tree"}'
```

**Delete an organization by id**
```
DELETE /api/v1/organizations/:id
```

Response
```
{
  "success": true,
  "data": {
    "id": "1"
  },
  "error": null
}
```

Example
```
curl -XDELETE http://192.168.55.55/api/v1/organizations/1 --header "Content-Type: application/json"
```

**Delete all organizations**
```
DELETE /api/v1/organizations
```

Response
```
{
  "success": true,
  "data": {
    "message": "All organization were deleted"
  },
  "error": null
}
```

Example
```
curl -XGET http://192.168.55.55/api/v1/organizations --header "Content-Type: application/json"
```

**Create relationship**
```
POST /api/v1/organizationRelationships
```

Input
```
{
    "org_name": "Paradise Island",
    "daughters": [
        {
            "org_name": "Banana tree",
            "daughters": [
                {"org_name": "Yellow Banana"},
                {"org_name": "Brown Banana"},
                {"org_name": "Green Banana"}
            ]
        },
        {
            "org_name":"Nestle"
        }
    ]
}
```

Response
```
{
  "success": true,
  "data": [
    {
      "id": 139,
      "type": "parent",
      "rel_owner_org_id": {
        "name": "Paradise Island",
        "people_count": 0,
        "owner_id": 1020435,
        "cc_email": "somefakecompony@pipedrivemail.com",
        "value": 305
      },
      "rel_linked_org_id": {
        "name": "Banana tree",
        "people_count": 0,
        "owner_id": 1020435,
        "cc_email": "somefakecompony@pipedrivemail.com",
        "value": 306
      },
      "add_time": "2015-12-30 13:31:46",
      "update_time": null,
      "active_flag": true
    },
    {
      "id": 143,
      "type": "parent",
      "rel_owner_org_id": {
        "name": "Paradise Island",
        "people_count": 0,
        "owner_id": 1020435,
        "cc_email": "somefakecompony@pipedrivemail.com",
        "value": 305
      },
      "rel_linked_org_id": {
        "name": "Nestle",
        "people_count": 0,
        "owner_id": 1020435,
        "cc_email": "somefakecompony@pipedrivemail.com",
        "value": 310
      },
      "add_time": "2015-12-30 13:31:47",
      "update_time": null,
      "active_flag": true
    }
  ],
  "error": null
}
```

Example

```
curl -XPOST http://192.168.55.55/api/v1/organizationRelationships --header "Content-Type: application/json" --header "Accept: application/json" -d '{"org_name":"Paradise Island", "daughters":[{"org_name":"Banana tree", "daughters":[{"org_name":"Yellow Banana"},{"org_name":"Brown Banana"},{"org_name":"Green Banana"}]},{"org_name":"Nestle"}]}'
```

**Get all relationships by organization id**
```
GET  /api/v1/organizationRelationships?org_id=:id
```

Response
```
{
  "success": true,
  "data": [
    {
      "id": "1",
      "org_id": {
        "id": "2",
        "name": "Paradise Island",
        "created_at": "2015-12-30 13:31:03"
      },
      "type": "parent",
      "linked_org_id": {
        "id": "3",
        "name": "Banana tree",
        "created_at": "2015-12-30 13:31:11"
      }
    },
    {
      "id": "5",
      "org_id": {
        "id": "2",
        "name": "Paradise Island",
        "created_at": "2015-12-30 13:31:03"
      },
      "type": "parent",
      "linked_org_id": {
        "id": "7",
        "name": "Nestle",
        "created_at": "2015-12-30 13:31:40"
      }
    }
  ],
  "error": null
}
```

Example

```
curl -XGET http://192.168.55.55/api/v1/organizationRelationships?org_id=1 --header "Content-Type: application/json"
```

**Remove all relationships**
```
DELETE /api/v1/organizationRelationships
```

Response
```
{
  "success": true,
  "data": {
    "message": "All organization relationships were deleted"
  },
  "error": null
}
```

Example:
```
curl -XDELETE http://192.168.55.55/api/v1/organizationRelationships --header "Content-Type: application/json"
```


**Remove all data**
```
GET /api/v1/clearAll
```

Response
```
{
  "success": true,
  "data": {
    "message": "All data has been removed"
  },
  "error": null
}
```

Example
```
curl -XGET http://192.168.55.55/api/v1/clearAll --header "Content-Type: application/json"
```
