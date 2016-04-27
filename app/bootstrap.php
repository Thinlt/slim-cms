<?php

define('DS', DIRECTORY_SEPARATOR);
define('BP', dirname(__DIR__));


/**
 * Environment initialization
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);
umask(0);

/* PHP version validation */
if (version_compare(phpversion(), '5.3.0', '<') === true) {
    if (PHP_SAPI == 'cli') {
        echo 'Magento supports PHP 5.3.0 or later. ' .
            'Please read http://devdocs.magento.com/guides/v1.0/install-gde/system-requirements.html';
    } else {
        echo <<<HTML
<div style="font:12px/1.35em arial, helvetica, sans-serif;">
    <p>Magento supports PHP 5.3.0 or later. Please read
    <a target="_blank" href="http://devdocs.magento.com/guides/v1.0/install-gde/system-requirements.html">
    Magento System Requirements</a>.
</div>
HTML;
    }
    exit(1);
}

require __DIR__.'/autoload.php';
require __DIR__.'/App.php';
require __DIR__.'/Middleware.php';
require __DIR__.'/Router.php';

if (ini_get('date.timezone') == '') {
    date_default_timezone_set('UTC');
}