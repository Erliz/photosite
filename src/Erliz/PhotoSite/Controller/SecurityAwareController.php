<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 25.11.2014
 */

namespace Erliz\PhotoSite\Controller;


abstract class SecurityAwareController extends ApplicationAwareController
{
    protected function getSessionService()
    {
        return $this->getApp()['session'];
    }
} 