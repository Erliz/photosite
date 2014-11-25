<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 25.11.2014
 */

namespace Erliz\PhotoSite\Controller;


use Symfony\Component\HttpFoundation\Request;

class AdminController extends ApplicationAwareController
{
    public function indexAction()
    {

        return $this->renderView('Admin/index.twig');
    }

    public function loginAction(Request $request)
    {

        return $this->renderView('Admin/login.twig');
    }

    public function logoutAction()
    {

    }
} 