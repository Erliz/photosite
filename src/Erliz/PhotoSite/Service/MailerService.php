<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   24.11.2014
 */

namespace Erliz\PhotoSite\Service;


use Silex\Application;
use Swift_Mailer;
use Swift_Message;

class MailerService
{
    /** @var Swift_Mailer */
    private $mailer;
    private $to;
    private $subject;

    public function __construct(Application $app)
    {
        $this->mailer = $app['mailer'];
        $this->to = $app['config']['mail']['to'];
        $this->subject = sprintf('Сообщение с сайта %s', $app['config']['front']['host']);
    }

    /**
     * @param array $formFields
     *
     * @return int
     */
    public function sendMessage(array $formFields)
    {
        $message = $this->getNewMessageInstance()
            ->setSubject($this->subject)
            ->setTo(array($this->to))
            ->setBody($this->generateMessageBody($formFields));

        return $this->mailer->send($message);
    }

    /**
     * @return Swift_Message
     */
    private function getNewMessageInstance()
    {
        return Swift_Message::newInstance();
    }

    /**
     * @param array $formFields
     *
     * @return string
     */
    private function generateMessageBody(array $formFields)
    {
        $message = "Об отправителе:\n";
        $message .= 'Имя: ' . $formFields['name'] . "\n";
        $message .= 'email: ' . $formFields['email'] . "\n";
        $message .= 'Телефон: ' . $formFields['phone'] . "\n";
        $message .= 'Сообщение:' . "\n";
        $message .= $formFields['content'];

        return $message;
    }
}
