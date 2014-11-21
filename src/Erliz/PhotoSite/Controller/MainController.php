<?php
/**
 * @author Stanislav Vetlovskiy
 * @date 18.11.2014
 */

namespace Erliz\PhotoSite\Controller;

use Symfony\Component\HttpFoundation\Response;

class MainController extends ApplicationAwareController
{
    public function indexAction()
    {
        return $this->renderView('Main/index.twig');
    }
} 