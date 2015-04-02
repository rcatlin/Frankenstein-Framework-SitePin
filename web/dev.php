<?php

/**
 * @see http://gonzalo123.com/2012/10/15/how-to-rewrite-urls-with-php-5-4s-built-in-web-server/
 */
if (preg_match('/\.(?:png|jpg|jpeg|gif|css|eot|svg|ttf|woff|woff2|otf|js)$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {
    include __DIR__ . '/app.php';
}
