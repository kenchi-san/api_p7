# api_p7
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/2ba1a0b7ae984c55b241a5c91d8b054a)](https://www.codacy.com/gh/kenchi-san/api_p7/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=kenchi-san/api_p7&amp;utm_campaign=Badge_Grade)
## Description



## Installation

Clone repository and install dependencies

```
git clone https://github.com/kenchi-san/api_p7.git
cd api_p7
composer install
```

Create a copy of .env file to .env.local with your own settings


## Initialize database

```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate


```
## Setting jwt key
```
generer one jwt key via openssl:
mkdir -p config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```
## Important
Don't forget to clean cache if you have any problem:
```
php bin/console cache:clear
```

## Load Fixtures

```
php bin/console doctrine:fixtures:load
or
composer reset
```
with "composer reset" you need to install "[symfony binary](https://symfony.com/doc/current/best_practices.html#use-the-symfony-binary-to-create-symfony-applications)"

## POSTMAN SETTING

[click here to access to postman configuration ](Postman%20folder/P7.postman_collection.json)



## API DOC
```
use this uri: "api/doc" 
```

## login
```
use uri: api/login_check in postman

use bellow information to make the conection
 "username": "user1@gmail.com",
 "password": "bibi"
 copy the token and past it in "Authorize" in api doc
```




