<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Tests\DataFixtures\ORM;


use DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Erliz\PhotoSite\Entity\Photo;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class PhotoDataLoader implements FixtureInterface, DependentFixtureInterface
{
    public function getDependencies()
    {
        return array('Erliz\PhotoSite\Tests\DataFixtures\ORM\AlbumDataLoader');
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @throws RuntimeException
     */
    public function load(ObjectManager $manager)
    {
        $dataFile = __DIR__ . '/dump/Photo.yml';
        if (!file_exists($dataFile)) {
            throw new RuntimeException(sprintf('No file exist with fixture data on "%s" path', $dataFile));
        }
        $dataList = Yaml::parse($dataFile);

        foreach ($dataList as $item) {
            $photo = new Photo();
            $photo->setId($item['id'])
                  ->setAlbum($manager->find('Erliz\PhotoSite\Entity\Album', $item['album']))
                  ->setTitle($item['title'])
                  ->setCreatedAt(new DateTime($item['created_at']))
                  ->setAvailable($item['is_available'])
                  ->setVertical($item['is_vertical']);

            if (!empty($item['weight'])) {
                $photo->setWeight($item['weight']);
            }


            $manager->persist($photo);
        }

        $manager->flush();
    }
}