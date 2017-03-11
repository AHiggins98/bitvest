#!/bin/sh
vendor/bin/php-cs-fixer fix app
vendor/bin/php-cs-fixer fix tests
vendor/bin/php-cs-fixer fix public
vendor/bin/php-cs-fixer fix config.php
