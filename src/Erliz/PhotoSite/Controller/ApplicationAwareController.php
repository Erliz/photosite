<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.2014
 */

namespace Erliz\PhotoSite\Controller;


use Doctrine\ORM\EntityManager;
use Silex\Application;

class ApplicationAwareController
{
    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return Application
     */
    protected function getApp()
    {
        return $this->app;
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        return $this->app['orm.em'];
    }

    protected function renderView($templatePath, array $variables = array())
    {
        return $this->getApp()['twig']->render($templatePath, $variables);
    }
}
