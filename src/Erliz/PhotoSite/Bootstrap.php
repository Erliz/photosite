<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 18.11.2014
 */

namespace Erliz\PhotoSite;


use Doctrine\ORM\EntityManager;
use Erliz\PhotoSite\Controller\AdminController;
use Erliz\PhotoSite\Controller\AlbumsController;
use Erliz\PhotoSite\Controller\ContactsController;
use Erliz\PhotoSite\Controller\LinksController;
use Erliz\PhotoSite\Controller\MainController;
use Erliz\PhotoSite\Controller\VideoController;
use Erliz\PhotoSite\Entity\Setting;
use Erliz\PhotoSite\Extension\Twig\AssetsExtension;
use Erliz\PhotoSite\Extension\Twig\PhotoExtension;
use Erliz\PhotoSite\Service\MailerService;
use Erliz\PhotoSite\Service\PhotoService;
use Erliz\PhotoSite\Service\SecurityService;
use Pimple;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\Provider\ServiceControllerServiceProvider;
use Symfony\Component\HttpFoundation\Request;

class Bootstrap implements ControllerProviderInterface
{
    private $prefix = __NAMESPACE__;

    /**
     * Returns routes to connect to the given application.
     *
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $app->register(new ServiceControllerServiceProvider());
        $this->setControllers($app);
        $controllersFactory = $this->getControllersFactory($app);

        $this->addSettings($app);
        $this->addServices($app);
        $this->addExtensions($app);

        return  $controllersFactory;
    }

    /**
     * @param Application $app
     *
     * @return Pimple
     */
    private function setControllers(Application $app)
    {
        $app[$this->prefix . '_main.controller'] = $app->share(function() use($app) {
            return new MainController($app);
        });
        $app[$this->prefix . '_contacts.controller'] = $app->share(function() use($app) {
            return new ContactsController($app);
        });
        $app[$this->prefix . '_video.controller'] = $app->share(function() use($app) {
            return new VideoController($app);
        });
        $app[$this->prefix . '_links.controller'] = $app->share(function() use($app) {
            return new LinksController($app);
        });
        $app[$this->prefix . '_albums.controller'] = $app->share(function() use($app) {
            return new AlbumsController($app);
        });
        $app[$this->prefix . '_admin.controller'] = $app->share(function() use($app) {
            return new AdminController($app);
        });
    }

    /**
     * @param Application $app
     *
     * @return ControllerCollection
     */
    private function getControllersFactory(Application $app)
    {
        $controllersFactory = $app['controllers_factory'];

        $controllersFactory->get('/', $this->prefix . '_main.controller:indexAction')
                           ->bind('erliz_photosite_index');

        $controllersFactory->get('/contacts/', $this->prefix . '_contacts.controller:indexAction')
                           ->bind('erliz_photosite_contacts_index');
        $controllersFactory->post('/contacts/mail/', $this->prefix . '_contacts.controller:mailAction')
                           ->bind('erliz_photosite_contacts_mail');

        $controllersFactory->get('/video/', $this->prefix . '_video.controller:indexAction')
                           ->bind('erliz_photosite_video_index');
        $controllersFactory->get('/links/', $this->prefix . '_links.controller:indexAction')
                           ->bind('erliz_photosite_links_index');

        $controllersFactory->get('/albums/', $this->prefix . '_albums.controller:indexAction')
                           ->bind('erliz_photosite_albums_index');
        $controllersFactory->get('/albums/id/{id}/',
            function ($id) use ($app) {
                return $app->redirect(
                    $app['url_generator']->generate('erliz_photosite_albums_view', array('id' => $id))
                );
            }
        );
        $controllersFactory->get('/albums/id/{id}.json', $this->prefix . '_albums.controller:viewJsonAction');
        $controllersFactory->get('/albums/id/{id}/{page}/', $this->prefix . '_albums.controller:viewAction')
                           ->assert('id', '\d+')
                           ->assert('page', '\d+')
                           ->value('page', 0)
                           ->bind('erliz_photosite_albums_view');

        $controllersFactory->get('/admin/', $this->prefix . '_admin.controller:indexAction')
                           ->bind('erliz_photosite_admin_index');
        $controllersFactory->get('/admin/login/', $this->prefix . '_admin.controller:loginAction')
                           ->bind('erliz_photosite_admin_login');

        $controllersFactory->get('/admin/upload/', $this->prefix . '_admin.controller:uploadAction')
                           ->bind('erliz_photosite_admin_upload');


        $controllersFactory->get('/admin/albums/', $this->prefix . '_admin.controller:albumsIndexAction')
                           ->bind('erliz_photosite_admin_albums');
        $controllersFactory->get('/admin/albums/{id}/', $this->prefix . '_admin.controller:albumEditAction')
                           ->assert('id', '\d+')
                           ->bind('erliz_photosite_admin_album_edit');
        $controllersFactory->post('/admin/albums/save/', $this->prefix . '_admin.controller:albumsSaveAction')
                           ->bind('erliz_photosite_admin_albums_save');

        return  $controllersFactory;
    }

    /**
     * @param Application $app
     */
    private function addSettings(Application $app)
    {
        $app->before(function (Request $request, Application $app) {

            /** @var EntityManager $em */
            $em = $app['orm.em'];
            $settings = array();
            /** @var Setting $setting */
            foreach ($em->getRepository('Erliz\PhotoSite\Entity\Setting')->findAll() as $setting) {
                $settings[$setting->getName()] = $setting->getValue();
            }

            $app['twig'] = $app->share($app->extend('twig', function($twig, $app) use ($settings) {
                $twig->addGlobal('general', $settings);

                return $twig;
            }));

        }, Application::EARLY_EVENT);
    }

    /**
     * @param Application $app
     */
    private function addExtensions(Application $app)
    {
        $app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
            $twig->addExtension(new AssetsExtension($app));
            $twig->addExtension(new PhotoExtension($app));
//            $twig->addExtension(new Twig_Extensions_Extension_Text());

            return $twig;
        }));
    }

    /**
     * @param Application $app
     */
    private function addServices(Application $app)
    {
        $app['photo.service'] = $app->share(function () {
            return new PhotoService();
        });
        $app['mailer.service'] = $app->share(function () use ($app) {
            return new MailerService($app);
        });
//        $app['security.service'] = $app->share(function () use ($app) {
//            return new SecurityService($app);
//        });
    }
}