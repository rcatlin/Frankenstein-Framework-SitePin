<?php

require_once __DIR__.'/../vendor/autoload.php';

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;
use SitePin\Component\Kernel;
use SitePin\Component\Request;
use SitePin\Component\Response;

$request = Request::createFromGlobals($_SERVER, $_GET, $_POST);

$kernel = new Kernel();

$debug = getenv('SITE_PIN_PROD_ENV') ? false : true;

// Init ORM
// TODO: define and load configuration from external file
$em = EntityManager::create(
    array( // connection
        'driver' => 'pdo_sqlite',
        'path' => __DIR__.'/../db.sqlite',
    ),
    Setup::createAnnotationMetadataConfiguration( // config
        array(
            __DIR__."/src"
        ),
        $debug
    )
);

// Add Entity Manager to Container
$kernel->getContainer()->add('entity_manager', $em);

// Init Twig
// TODO: define and load configuration from external file
$loader = new Twig_Loader_Filesystem(
    __DIR__.'/../src/SitePin/Resources/templates'
);
$twig = new Twig_Environment(
    $loader,
    array(
        'cache' => __DIR__.'/../cache',
        'auto_reload' => $debug
    )
);

// Add Twig to Container
$kernel->getContainer()->add('twig', $twig);

// Handle Request
$response = $kernel->handle($request);

if ($response && $response instanceof Response) {
    $response->send();
}
