<?php
/**
 * @author Stanislav Vetlovskiy
 * @date 18.11.2014
 */

namespace Erliz\PhotoSite\Controller;


class MainController extends ApplicationAwareController
{
    public function indexAction()
    {
        return $this->renderView('Main/index.twig');
    }
} 