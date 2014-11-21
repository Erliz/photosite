<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Controller;


class VideoController extends ApplicationAwareController
{
    public function indexAction()
    {
        return $this->renderView('Video/index.twig');
    }
}
