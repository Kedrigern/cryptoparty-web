cryptoparty-web
===============

Cryptoparty webpresentation and CMS based on Nette framework.

Deploy
------
1. make dirs `log`, `temp` writable: ```chmod -R 775 log temp```
1. use composer to fetch backend libs: ```composer install``` (and maybe you need also `composer update`)
1. prepare DB by `app/model/createDB.sql`
1. customize `app/config/config.local.neon` (configure your DB connection)