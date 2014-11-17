<?php
/**
 * @author Stanislav Vetlovskiy
 * @date 18.11.2014
 */

namespace Erliz\PhotoSite\Controller;

use Symfony\Component\HttpFoundation\Response;

class MainController
{
    public function indexAction()
    {
        return new Response('hello world');
    }
} 