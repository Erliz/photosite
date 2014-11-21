<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Controller;


class LinksController extends ApplicationAwareController
{
    public function indexAction()
    {
        return $this->renderView('Links/index.twig');
    }
}
