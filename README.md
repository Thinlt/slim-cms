# Git Repository CMS

# APIs:

## Authorization:

 Authorization is header key value or request body key value and value is token: "&lt;token_string&gt;". <br/>
 Ext: "token e5fb64ab8fd069f846805fb004008472bd758407"

HEADER:

    {
        "Authorization": "token e5fb64ab8fd069f846805fb004008472bd758407"
    }



## Get Composer Json File:

POST: api/composer/json/user/{user@example.com}

REQUEST BODY:

    {
        "packages": [
            "Owner/Repo"
        ]
    }

    OR

    {
        "packages": [
            {
                "name": "Owner/Repo",
                "version": "1.0.0"
            }
        ]
    }

RESPONSE:

    {
        "repositories": [
            {
                "type": "composer",
                "url": "https://www.example.com/packages/a06ad4394c72cc249fd54b60e792318427a14b8f/"
            }
        ],
        "require": {
            "Magestore/Supercampaign-Magento1": "1.0.0",
            "Magestore/Supercampaign-Magento2": "1.0.1",
            "Magestore/Supercampaign-Magento3": "*"
        },
        "config": {
            "secure-http": true
        }
    }

    Or not found user token:

    {
        "message": "User not generated token",
        "error": true
    }

## User - Add new user

Method: **POST api/user/add**

Post data:

        {
            "user_name": "<email>",
            "fullname": "<string>",
            "packages": [ "1", "2", "3", ... ]
        }

Response data:

        {
             "user_id": "<int>",
             "token": "<string>",
             "packages": [ "1", "2", ... ],
             "message": "",
             "success": true|false,
             "error": true|false
        }
    
## User - View a user by user name

Method: **GET api/user/view/{user@example.com}**

Response:

        {
            "message": "",
            "error": false,
            "id": "19",
            "user_name": "abc@def.com",
            "password": "99bb06fd12c30f73f6a5732004512fa6801c67f6",
            "token": "d0e39be13c309177ebf3363a416f7615483271e6",
            "name": "John Nathan",
            "role_id": ""
        }

## User - Add package (Repo)
Description: Add the Repository to one user.

Method: **POST api/user/{id}/repos/add**

Post data:
    
    {
        "repo_url": ["<http://github.com/_/_.git>"]
    }
    
    OR
    
    {
        "owner": "", 
        "repo": ""
    }
    
    OR
    
    {
        "repos": [
            {
                "owner": "", 
                "repo": ""
            }, 
            ...
        ]
    }
    

Response:

        {
            "message": "Success!",
            "repo_ids": [
                "58",
                "54",
                "19",
                "59"
            ],
            "error": false,
            "success": true
        }
        

## User - View packages (Repo)
Description: View Repositories had assigned to one user.

Method: **GET api/user/repos/view/{email}**

Method: **GET api/user/{id}/repos/view**

Response:

        {
            "message": "Success!",
            "repo_ids": [
                "2",
                "1"
            ],
            "error": false,
            "success": true
        }
        

## Repo - Versions
Description: View versions of repository.

Method: **GET /repo/{vendor_name}/{repo_name}/versions**

Method: **POST /repo/versions**

Post data: { 'repo_url': 'https://github.com/Magestore/Membership-Magento2.git' }

Response:

        {
            "versions": [
                "1.0.0"
            ]
        }


