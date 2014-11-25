<?php
/**
 * @author Stanislav Vetlovskiy
 * @date   25.11.2014
 */

namespace Erliz\PhotoSite\Service;


use Silex\Application;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\User\User;

class SecurityService
{
    /** @var Application */
    private $app;
    /** @var SecurityContext */
    private $securityCore;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->securityCore = $app['security'];
    }

    public function setUserData($name, array $userSecurityData)
    {
        array_merge(
            $this->app['security.firewalls']['admin']['users'],
            array(
                $name => array(
                    $userSecurityData['role'],
                    $this->encodePassword($userSecurityData['password'])
                )
            )
        );
    }

    public function encodePassword($rawPassword)
    {
        return $this->getEncoder()->encodePassword($rawPassword, $this->getSalt());
    }

    /**
     * @return mixed
     */
    private function getUser()
    {
        return $this->getToken()->getUser();
    }

    /**
     * @return null|\Symfony\Component\Security\Core\Authentication\Token\TokenInterface
     */
    private function getToken()
    {
        return $this->securityCore->getToken();
    }

    /**
     * @return PasswordEncoderInterface
     */
    private function getEncoder()
    {
        return $this->app['security.encoder_factory']->getEncoder($this->getUser());
    }

    private function getSalt()
    {
        return $this->getUser()->getSalt();
    }
}
