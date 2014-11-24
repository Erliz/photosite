<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Controller;


use Erliz\PhotoSite\Service\MailerService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ContactsController extends ApplicationAwareController
{
    public function indexAction()
    {
        return $this->renderView('Contacts/index.twig');
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function mailAction(Request $request)
    {
        /** @var MailerService $mailer */
        $mailer = $this->getApp()['mailer.service'];

        $fields = array(
            'name'    => $request->get('name'),
            'email'   => $request->get('email'),
            'phone'   => $request->get('phone'),
            'content' => $request->get('content'),
        );
        foreach ($fields as $key => $value) {
            $fields[$key] = trim(strip_tags($value));
        }

        return new JsonResponse(array('data' => $mailer->sendMessage($fields)));
    }
}
