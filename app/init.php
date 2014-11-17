<?php
/**
* @author Stanislav Vetlovskiy
* @date 18.11.2014
*/


$app = new Silex\Application();
$app['debug'] = true;

$app['current_url'] = !empty($_SERVER['DOCUMENT_URI']) ? $_SERVER['DOCUMENT_URI'] : '/';
$app['config.templates.path'] = array(
    __DIR__.'/../src/Erliz/PhotoSite/Resources/view'
);

// Providers
$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array('twig.path' => $app['config.templates.path'])
);
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->getExtension('core')->setNumberFormat(0, '.', ' ');

    return $twig;
}));

$app->mount('/', new Erliz\PhotoSite\Bootstrap());

return $app;
