<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 07.12.2014
 */

namespace Erliz\PhotoSite\Controller;


class LandingController extends ApplicationAwareController
{
    public function showYourSelfAction()
    {
        return $this->renderView('Landing/showYourSelf.twig');
    }
} 