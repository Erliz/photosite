<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 18.11.2014
 */

namespace Erliz\PhotoSite;


use Erliz\PhotoSite\Controller\MainController;
use Pimple;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use Silex\Provider\ServiceControllerServiceProvider;

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

        return  $controllersFactory;
    }

    /**
     * @param Application $app
     *
     * @return Pimple
     */
    private function setControllers(Application $app)
    {
        $app[$this->prefix . '_main.controller'] = $app->share(function() {
            return new MainController();
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

        return  $controllersFactory;
    }
}