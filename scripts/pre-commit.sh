#!/usr/bin/env bash

ROOT="/data/devo/current"

PHP_CS_FIXER="vendor/bin/php-cs-fixer"
HAS_PHP_CS_FIXER=false

if [ -x vendor/bin/php-cs-fixer ]; then
HAS_PHP_CS_FIXER=true
fi

if $HAS_PHP_CS_FIXER; then
git status --porcelain | grep -e '^[AM]\(.*\).php$' | cut -c 3- | while read line; do
        $PHP_CS_FIXER fix --verbose "$line";
        git add "$line";
    done
else
echo ""
    echo "Please install php-cs-fixer, e.g.:"
    echo ""
    echo " composer require --dev fabpot/php-cs-fixer:dev-master"
    echo ""
fi

echo "pre commit hook finish"
