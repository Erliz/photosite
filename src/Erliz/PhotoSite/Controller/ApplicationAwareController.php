<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.2014
 */

namespace Erliz\PhotoSite\Controller;


use Silex\Application;

class ApplicationAwareController
{
    /** @var Application */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function renderView($templatePath, array $variables = array())
    {
        return $this->getApp()['twig']->render($templatePath, $variables);
    }
}
