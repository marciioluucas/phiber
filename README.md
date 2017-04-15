# Phiber - alfa version 0.1

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

> I made this library to increase my knowledge and to help my friends programmers build apps in PHP more fast, cause I see an big difficulty to build SQLs and i think very boring. 

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
    "language": "pt_br", /*preffer language*/
    "link": {
      "database_technology": "mysql", //database technology (not implemented)
      "database_name": "phiber_test", //name of your database
      "url": "mysql:host=localhost;dbname=teste_phiber", //Your driver connection
      "user": "root", // user of your db
      "password": "", // password
      "connection_cache": true // connection cache (not tested)
    },
    "log": true, // if you want a log, (just work in command line)
    "execute_queries": true, // if you want to execute the queries ex: select, create, update etc.
    "code_sync": false // technology to sync the code with db in realtime (recommended just to development enviroments).
  }
}
```


### Development
Todo

First Tab:
```sh
$ node app
```

Second Tab:
```sh
$ gulp watch
```

(optional) Third:
```sh
$ karma test
```
#### Building for source
For production release:
```sh
$ gulp build --prod
```
Generating pre-built zip archives for distribution:
```sh
$ gulp build dist --prod
```
### Dock
### Todos

 - Write MOAR Tests
 - Add Night Mode

License
----

MIT


**Free Software, Hell Yeah!**

[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax)


   [dill]: <https://github.com/joemccann/dillinger>
   [git-repo-url]: <https://github.com/joemccann/dillinger.git>
   [john gruber]: <http://daringfireball.net>
   [df1]: <http://daringfireball.net/projects/markdown/>
   [markdown-it]: <https://github.com/markdown-it/markdown-it>
   [Ace Editor]: <http://ace.ajax.org>
   [node.js]: <http://nodejs.org>
   [Twitter Bootstrap]: <http://twitter.github.com/bootstrap/>
   [jQuery]: <http://jquery.com>
   [@tjholowaychuk]: <http://twitter.com/tjholowaychuk>
   [express]: <http://expressjs.com>
   [AngularJS]: <http://angularjs.org>
   [Gulp]: <http://gulpjs.com>

   [PlDb]: <https://github.com/joemccann/dillinger/tree/master/plugins/dropbox/README.md>
   [PlGh]: <https://github.com/joemccann/dillinger/tree/master/plugins/github/README.md>
   [PlGd]: <https://github.com/joemccann/dillinger/tree/master/plugins/googledrive/README.md>
   [PlOd]: <https://github.com/joemccann/dillinger/tree/master/plugins/onedrive/README.md>
   [PlMe]: <https://github.com/joemccann/dillinger/tree/master/plugins/medium/README.md>
   [PlGa]: <https://github.com/RahulHP/dillinger/blob/master/plugins/googleanalytics/README.md>
