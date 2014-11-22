<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 18.11.2014
 */

namespace Erliz\PhotoSite;


use Doctrine\ORM\EntityManager;
use Erliz\PhotoSite\Controller\ContactsController;
use Erliz\PhotoSite\Controller\LinksController;
use Erliz\PhotoSite\Controller\MainController;
use Erliz\PhotoSite\Controller\VideoController;
use Erliz\PhotoSite\Entity\Setting;
use Erliz\PhotoSite\Extension\Twig\AssetsExtension;
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
    }

    /**
     * @param Application $app
     *
     * @return ControllerCollection
     */
    private function getControllersFactory(Application $app)
    {
        $controllersFactory = $app['controllers_factory'];
        $controllersFactory->get('/', $this->prefix . '_main.controller:indexAction');
        $controllersFactory->get('/contacts/', $this->prefix . '_contacts.controller:indexAction');
        $controllersFactory->get('/video/', $this->prefix . '_video.controller:indexAction');
        $controllersFactory->get('/links/', $this->prefix . '_links.controller:indexAction');

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
//            $twig->addExtension(new Twig_Extensions_Extension_Text());

            return $twig;
        }));
    }
}