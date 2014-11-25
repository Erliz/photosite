<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 25.11.2014
 */

namespace Erliz\PhotoSite\Controller;


use Symfony\Component\HttpFoundation\Request;

abstract class SecurityAwareController extends ApplicationAwareController
{
    protected function getSessionService()
    {
        return $this->getApp()['session'];
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    protected function getLastError(Request $request)
    {
        /** @var \Closure $lastErrorClosure */
        $lastErrorClosure = $this->getApp()['security.last_error'];

        return $lastErrorClosure($request);
    }
} 