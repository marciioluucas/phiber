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
```
InnerJoin example:
$phiber = new Phiber();

$phiber->setTable("user");
$phiber->setFields(["user.id","user.name","user.email"]);
$phiber->add($phiber->restrictions->join("user_address", ["pk_user", "fk_user"]));
$phiber->add($phiber->restrictions->and($phiber->restrictions->equals("user.id","1"), $phiber->restrictions->like("user.name","Marcio") ));
$phiber->add($phiber->restrictions->limit(15));
$phiber->add($phiber->restrictions->offset(5));
$phiber->add($phiber->restrictions->orderBy(['user.id ASC']));
$phiber->select(); // Execute query
echo $phiber->show(); // After execute, prints the generated query
Generate->
SELECT user.id, user.name, user.email FROM user INNER JOIN user_address ON pk_user = fk_user  WHERE (user.id = :condition_user.id AND user.name LIKE CONCAT('%',:condition_user.name,'%')) ORDER BY user.id ASC LIMIT 15  OFFSET 5;
```

### TODOS:

 - In the create method, make the support for the composition and others.
 - Split classes correctly
 - Do Relationables tables

License
----

MIT


**Free Software, Hell Yeah!**
