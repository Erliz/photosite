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
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
        'security.firewalls' => array(
            'login' => array(
                'pattern' => '^/admin/login/',
                'anonymous' => true
            ),
            'admin' => array(
                'pattern' => '^/admin/',
                'form' => array('login_path' => '/admin/login/', 'check_path' => '/admin/login/check'),
                'users' => array(
                    'admin' => array('ROLE', ''),
                ),
            )
        )
    ));
$app->register(new Silex\Provider\SwiftmailerServiceProvider, array(
    'swiftmailer.options' => array(
        'host'       => $app['config']['mail']['host'],
        'port'       => $app['config']['mail']['port'],
        'username'   => $app['config']['mail']['username'],
        'password'   => $app['config']['mail']['password'],
        'encryption' => $app['config']['mail']['encryption'],
        'auth_mode'  => $app['config']['mail']['auth_mode']
    )
));
$app->register(new Silex\Provider\DoctrineServiceProvider, array(
    'db.options' => array(
        'driver'    => $app['config']['db']['driver'],
        'host'      => $app['config']['db']['host'],
        'dbname'    => $app['config']['db']['name'],
        'user'      => $app['config']['db']['user'],
        'password'  => $app['config']['db']['password'],
        'charset'   => $app['config']['db']['charset'],
    )
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
