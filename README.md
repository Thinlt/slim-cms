# slim-cms

# APIs:

* Authorization:

 Authorization is header key value or request body key value and value is token <token_string>
 ext: "token e5fb64ab8fd069f846805fb004008472bd758407"

HEADER:

 {
    "Authorization": "token e5fb64ab8fd069f846805fb004008472bd758407"
 }



* Get Composer Json File

POST: api/composer/json/user/<user@example.com>

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

