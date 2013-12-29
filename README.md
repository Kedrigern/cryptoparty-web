cryptoparty-web
===============

Cryptoparty webpresentation and CMS based on [Nette framework](http://nette.org).

Deploy
------
1. use [composer](http://getcomposer.org) to fetch backend libs: ```composer install```
1. prepare DB by `app/model/createDB.sql`
1. customize `app/config/config.local.neon` (configure your DB connection)

For libs update just run:
```composer update```