<?php
/**
* @author Stanislav Vetlovskiy
* @date 18.11.2014
*/

/**
 * @var $loader Composer\Autoload\ClassLoader
 */

$app = new Silex\Application();

$app['config'] = \Symfony\Component\Yaml\Yaml::parse(__DIR__ . '/config/settings.yml');

$app['debug'] = $app['config']['debug'];
$app['current_url'] = !empty($_SERVER['DOCUMENT_URI']) ? $_SERVER['DOCUMENT_URI'] : '/';
$app['config.templates.path'] = array(
    __DIR__.'/../src/Erliz/PhotoSite/Resources/views'
);

// Providers
$app->register(
    new Silex\Provider\TwigServiceProvider(),
    array('twig.path' => $app['config.templates.path'])
);
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider, array(
    "db.options" => array(
        'driver'    => $app['config']['db']['driver'],
        'host'      => $app['config']['db']['host'],
        'dbname'    => $app['config']['db']['name'],
        'user'      => $app['config']['db']['user'],
        'password'  => $app['config']['db']['password'],
        'charset'   => $app['config']['db']['charset'],
    ),
));

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

$app->register(new Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider, array(
    "orm.proxies_dir" => "cache/doctrine/proxies",
    "orm.em.options" => array(
        "mappings" => array(
            array(
                "type" => "annotation",
                "use_simple_annotation_reader" => false,
                "namespace" => "Erliz\\PhotoSite\\Entity",
                "path" => __DIR__."/../src/Erliz/PhotoSite/Entity",
            )
        )
    ),
));


$app['twig'] = $app->share($app->extend('twig', function($twig) {
    $twig->getExtension('core')->setNumberFormat(0, '.', ' ');

    return $twig;
}));

$app->mount('/', new Erliz\PhotoSite\Bootstrap());

return $app;
