<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Controller;


class ContactsController extends ApplicationAwareController
{
    public function indexAction()
    {
        return $this->renderView('Contacts/index.twig');
    }
}
