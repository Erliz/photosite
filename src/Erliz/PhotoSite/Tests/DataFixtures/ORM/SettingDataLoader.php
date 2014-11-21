<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Tests\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Erliz\PhotoSite\Entity\Setting;

class SettingDataLoader implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $data = array(
            'title'       => 'Фотограф Клементьева Екатерина',
            'author'      => 'Клементьева Екатерина',
            'email'       => 'Katelyn.K@ya.ru',
            'keywords'    => 'портфолио, фотографии, портрет, пейзаж, предметная съемка, фотограф, клементьева, екатерина, klementyeva, фото, katelyn, photo',
            'site_url'    => 'http://katelyn.ru/',
            'description' => 'Официальный сайт. Портреты, детская съемка, студийная съемка,  НЮ, коммерческая съемка.',
            'password'    => 'test_password',
            'admin_key'   => 'test_key',
            'header_text' => 'Фотограф Клементьева Екатерина'
        );

        foreach ($data as $name => $value) {
            $setting = new Setting();
            $setting->setName($name)->setValue($value);
            $manager->persist($setting);
        }

        $manager->flush();
    }
}