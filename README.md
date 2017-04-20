# Phiber - ALPHA version 0.1

[![Imgur](http://i.imgur.com/Ad02NS2.png)](https://nodesource.com/products/nsolid)

Phiber is a simples ORM framework that helps you code your applications more fast.

  - MySQL integration
  - Object Orientation
  - Without SQL

# New Features!

  - Persistence
  - Tables Creation (NON RELATIONABLES)


You can also:
  - See generated SQL
  - Activate console logs.
  
This library has been made by a student of Technology in systems to Internet of Morrinhos - GO, Brazil.
https://www.ifgoiano.edu.br/morrinhos

Criador [Márcio Lucas]

> I made this library to increase my knowledge and to help my friends programmers build apps in PHP more fast, cause I see an big difficulty to build SQLs and i think it is very boring. 

This library are in alpha test, I don't recommend to production environments.

### Technology

Phiber uses just pure PHP and until now only has been builded the part to MySQL 5.5+.
*In the next versions we will implement in BDs like PostgreSQL and Oracle.



### Installation

Phiber requires  PHP 5.3.3+ to run and MySQL 5.5+.

Installing Phiber in your project.

You can install by two ways. First is making the Download from github repository and putting the folder of your project
The second way is installing via composer with this command bellow:
```sh
$ composer install marciioluucas/phiber
```

### Config

To config Phiber is very simple,
inside the folder phiber or vendor/phiber gonna have an archive .json called phiber_config.
You are therefore the credentials of the bank and other settings.

phiber/phiber_config.json
```json
{
  "phiber": {
    "language": "pt_br", 
    "link": {
      "database_technology": "mysql", 
      "database_name": "phiber_test", 
      "url": "mysql:host=localhost;dbname=teste_phiber", 
      "user": "root", 
      "password": "", 
      "connection_cache": true 
    },
    "log": true, 
    "execute_queries": true, 
    "code_sync": false 
  }
}
```


### Development
Todo
Making a create in DB

model/User.php
```php
class User {
    private $name;
    
    function getName(){
        //Implement
    }
    function setName() {
        //Implement
    }

    public function create(){
        try {
            require 'phiber/Phiber.php';
            include_once 'phiber/PhiberAutoload.php';
            if($this->name != null){
                return \phiber\Phiber::openPersist()->create($this));
            }
    return "ERROR: Name null";
    }catch (Exception $e) {
        throw new Exception($e->getMesage);
    }
}
```

### TODOS://

 - foo
 - bar

License
----

MIT


**Free Software, Hell Yeah!**
