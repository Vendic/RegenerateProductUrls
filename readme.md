Magento 2 RegenerateProductUrls Extension [![Build Status](https://travis-ci.org/Vendic/RegenerateProductUrls.svg?branch=master)](https://travis-ci.org/Vendic/RegenerateProductUrls)
=====================

Description
-----------
This module regenerates all product Urls -of every store view- once every day with a cron job. The module is based mostly on [Iazel/RegenUrl](https://github.com/Iazel/magento2-regenurl). The difference is that [Iazel/RegenUrl](https://github.com/Iazel/magento2-regenurl) generated the urls with the CLI and this modules does this with a cron job.

Warning
-------------
This module is not tested thoroughly! Test it on a staging environment and always back up your database before running the cron

Requirements
------------
- PHP 7.0

Compatibility
-------------
- Magento >= 2.1

Installation Instructions
-------------------------
```
composer require vendic/module-regenerateproducturls
php bin/magento setup:upgrade
```

Manual activation 
-------------------------
- Install [n98-magerun2](https://github.com/netz98/n98-magerun2)
```
n98-magerun2.phar sys:cron:run vendic_regenerateurls
```

Uninstallation
--------------
```
composer remove vendic/module-regenerateproducturls
```

Support
-------
If you have any issues with this extension, open an issue on [GitHub](https://github.com/vendic/Vendic_RegenerateProductUrls/issues).

Contribution
------------
Any contribution is highly appreciated. The best way to contribute code is to open a [pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Tjitse Efde

Licence
-------
[MIT](https://github.com/Vendic/RegenerateProductUrls/blob/master/LICENSE)

Copyright
---------
(c) 2017 Vendic


### About Vendic
[Vendic - Magento 2](https://vendic.nl "Vendic Homepage") develops technically challenging e-commerce websites using Magento 2. Feel free to check out our projects on our website.

