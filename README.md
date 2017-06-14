[![Build Status](https://travis-ci.org/marciioluucas/phiber.svg?branch=master)](https://travis-ci.org/marciioluucas/phiber)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/db9f41d9b8144d27ab90a0350cb25a28)](https://www.codacy.com/app/marciioluucas/phiber?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=marciioluucas/phiber&amp;utm_campaign=Badge_Grade)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/marciioluucas/phiber/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/marciioluucas/phiber/?branch=master)
[![GitHub issues](https://img.shields.io/github/issues/marciioluucas/phiber.svg)](https://github.com/marciioluucas/phiber/issues)
[![GitHub forks](https://img.shields.io/github/forks/marciioluucas/phiber.svg)](https://github.com/marciioluucas/phiber/network)
[![GitHub stars](https://img.shields.io/github/stars/marciioluucas/phiber.svg)](https://github.com/marciioluucas/phiber/stargazers)
[![GitHub license](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/marciioluucas/phiber/master/license)
[![Twitter](https://img.shields.io/twitter/url/https/github.com/marciioluucas/phiber.svg?style=social)](https://twitter.com/intent/tweet?text=Wow:&url=%5Bobject%20Object%5D)

# Phiber - ALPHA version 0.1

[![Imgur](http://i.imgur.com/Ad02NS2.png)](https://marciioluucas.github.io/phiber)

Phiber is a simple ORM framework that helps you code your applications faster.

  - MySQL integration
  - Object Orientation
  - Without SQL

# New Features!

  - Persistence
  - Build websites, apps and api without a single SQL query line.
  - Choose if you wanna make your SQL manually or by Object Mapping.
  


You can also:
  - See generated SQL
  - Activate console logs.
  
This library has been made by a Internet Systems Tecnology's student from Morrinhos - GO, Brazil.

[![IFGoiano](https://img.shields.io/badge/IF-Goiano-brightgreen.svg)](https://www.ifgoiano.edu.br/morrinhos)

Creator [Márcio Lucas]

> I made this library to increase my knowledge and to help my programmer friends build apps in PHP faster, because I see big difficulty to build SQLs and i think it is very boring. 

This library are in alpha test, I don't recommend to production environments.

### Technology

Phiber uses just pure PHP and until now only has been builded the part to MySQL 5.5+.
*In the next versions we will implement in BDs like PostgreSQL and Oracle.


### Dependencies

Phiber have only depdencies with Composer.


### Installation

Phiber requires  PHP 5.3.3+ to run and MySQL 5.5+.

Installing Phiber in your project.

The Phiber instalation is made by Composer, using the console code below:
```sh
$ composer require marciioluucas/phiber
```

### Config

To configure Phiber is very simple,
you have to create an archive called phiber_config.json in your project root path 
same like this.

$ROOT_PROJECT_PATH/phiber_config.json
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
    "execute_queries": true
  }
}
```

### Examples
Now we will create a crud class using Phiber

Notice this class, this is where magic happens.
model/User.php
```php

namespace test;
use bin\Restrictions;
use Exception;
use phiber\Phiber;

class User
{

    private $id;
    private $name;
    private $email;
    private $password;

    function get($prop)
    {
        return $this->$prop;
    }

    function set($prop, $value)
    {
        $this->$prop = $value;
    }

    public function validateInfos()
    {
        if ($this->name != null && $this->name != "") {
            if ($this->email != null && $this->email != "") {
                if ($this->password != null && $this->password != "") {
                    return true;
                }
            }
        }
        return false;
    }

    public function create()
    {
        try {
        $phiber = new Phiber();
        $criteria = $phiber->openPersist($this)
            if ($this->validateInfos()) {
                if ($criteria->create()) {
                    return json_encode(
                        [
                            "message" => "Success!!!",
                            "type_message" => "INFO"
                        ]
                    );
                } else {
                    return json_encode(
                        [
                            "message" => "Someting wrong happened",
                            "type_message" => "ERROR"
                        ]
                    );
                }
            } else {
                return json_encode(
                    [
                        "message" => "Invalid past values",
                        "type_message" => "ERROR"
                    ]
                );
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function retreave()
    {
        $phiber = new Phiber();
        $criteria = $phiber->openPersist($this)
        $restrictionName = "";
        $restrictionEmail = "";

        /* Here I created a retriction for each attribute */
        if ($this->name != null && $this->name != "") {
            $restrictionName = $criteria->restrictions()->like("name", $this->name);
            $criteria->add($restrictionName);

        } else if ($this->email != null && $this->email != "") {
            $restrictionEmail = $criteria->restrictions()->like("email", $this->email);
            $criteria->add($restrictionEmail);

        }
        /* And here I created a conjunction "AND" with the two restrictions*/
        $criteria->add($criteria->restrictions()->and($restrictionEmail,$restrictionName));
        return json_encode($criteria->select());
    }

    public function update()
    {
        try {
            $phiber = new Phiber();
            $criteria = $phiber->openPersist($this);
            
            $criteria->add($criteria->restrictions()->equals("id", $this->id));
            /*  Here I added a condition "equal" in WHERE of the query.
                The responsible class that creates the query, will return something like this.
                Select from user where id = :condition_id;
                After that, it will be done the binding of values and will be substituted
                the ":condition_id" by your value.
                Read the API doc book to know more about restrictions.
            */

            if ($this->validadeInfos()) {
                if ($criteria->update($this)) {
                    return json_encode(
                        [
                            "message" => "Success!!!",
                            "type_message" => "INFO"
                        ]
                    );
                } else {
                    return json_encode(
                        [
                            "message" => "Someting wrong happened",
                            "type_message" => "ERROR"
                        ]
                    );
                }
            } else {
                return json_encode(
                    [
                        "message" => "Invalid past values",
                        "type_message" => "ERROR"
                    ]
                );
            }

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function delete()
    {
        $phiber = new Phiber();
        $criteria = $phiber->openPersist($this)

        $criteria->add($criteria->restrictions()->eq("id", $this->id));
        /*  Here I added a condition "equal" in WHERE of the query.
            The responsible class that creates the query, will return something like this.
            Select from user where id = :condition_id;
            After that, it will be done the binding of values and will be substituted
            the ":condition_id" by your value.
            Read the API doc book to know more about restrictions.
        */

        if ($criteria->delete($this)) {
            return json_encode(
                [
                    "message" => "Success!!!",
                    "type_message" => "INFO"
                ]
            );
        } else {
            return json_encode(
                [
                    "message" => "Someting wrong happened",
                    "type_message" => "ERROR"
                ]
            );
        }
    }

    
}

```

### TODOS:

 - In the create method, make the support for the composition and others.
 - Split classes correctly
 - Do Relationables tables

License
----

MIT


**Free Software, Hell Yeah!**
