<?php

/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.2014
 */

namespace Erliz\PhotoSite\Tests\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Erliz\PhotoSite\Entity\Setting;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class SettingDataLoader implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    function load(ObjectManager $manager)
    {
        $dataFile = __DIR__ . '/dump/Setting.yml';
        if (!file_exists($dataFile)) {
            throw new RuntimeException(sprintf('No file exist with fixture data on "%s" path', $dataFile));
        }
        $dataList = Yaml::parse($dataFile);

        foreach ($dataList as $item) {
            $setting = new Setting();
            $setting->setName($item['name'])->setValue($item['value']);
            $manager->persist($setting);
        }

        $manager->flush();
    }
}