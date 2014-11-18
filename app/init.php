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

$app->register(new Silex\Provider\DoctrineServiceProvider, array(
    "db.options" => array(
        'mysql_main' => array(
            'driver'    => 'pdo_mysql',
            'host'      => 'local',
            'dbname'    => 'my_database',
            'user'      => 'my_username',
            'password'  => 'my_password',
            'charset'   => 'utf8',
        )
    ),
));

$app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
    "orm.proxies_dir" => "cache/doctrine/proxies",
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type" => "annotation",
                "namespace" => "Erliz\PhotoSite\Entity",
                "path" => __DIR__."/../src/Erliz/PhotoSite/Entity",
            )
        ),
    ),
));


$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->getExtension('core')->setNumberFormat(0, '.', ' ');

    return $twig;
}));

$app->mount('/', new Erliz\PhotoSite\Bootstrap());

return $app;
