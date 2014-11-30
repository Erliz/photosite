<?php
/**
* @author Stanislav Vetlovskiy
* @date 18.11.2014
*/

/**
 * @var $loader Composer\Autoload\ClassLoader
 */

$app = new Silex\Application();

$config = \Symfony\Component\Yaml\Yaml::parse(__DIR__ . '/config/settings.yml');
$config['app'] = array('path' => __DIR__);
$app['config'] = $config;
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
                'form' => array(
                    'login_path' => '/admin/login/',
                    'check_path' => '/admin/login_check'
                ),
                'users' => array(
                    // move to secure service
                    'admin' => array(
                        $app['config']['security']['admin']['role'],
                        $app['config']['security']['admin']['password'],
                    ),
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
$app->register(new Erliz\PhotoSite\Provider\FileUploadExtensionProvider, array(
    'fileupload.options' => array(
        'upload_dir' => __DIR__ . '/' . $app['config']['static']['path']['file'] . '/temp/',
        'upload_url' => $app['config']['static']['scheme'] . '://' . $app['config']['static']['host'] . '/files/temp/',
        'orient_image' => true,
        'print_response' => false,
        'image_versions' => array(
            '' => array(
                'max_width' => 3320,
                'max_height' => 1080,
                'jpeg_quality' => 100
            ),
            'thumbnail' => array(
                'max_width' => 264,
                'max_height' => 176,
                'jpeg_quality' => 100
            )
        )
    )
));

Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
Gedmo\DoctrineExtensions::registerAnnotations();

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

$timestampableListener = new Gedmo\Timestampable\TimestampableListener();
$timestampableListener->setAnnotationReader(new Doctrine\Common\Annotations\AnnotationReader());
$app['dbs.event_manager']['default']->addEventSubscriber($timestampableListener);


$app['twig'] = $app->share($app->extend('twig', function($twig) {
    $twig->getExtension('core')->setNumberFormat(0, '.', ' ');

    return $twig;
}));

$app->mount('/', new Erliz\PhotoSite\Bootstrap());

return $app;
