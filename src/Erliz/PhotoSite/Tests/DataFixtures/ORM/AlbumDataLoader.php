<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Tests\DataFixtures\ORM;


use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Erliz\PhotoSite\Entity\Album;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class AlbumDataLoader implements FixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     *
     * @throws RuntimeException
     */
    public function load(ObjectManager $manager)
    {
        $dataFile = __DIR__ . '/dump/Album.yml';
        if (!file_exists($dataFile)) {
            throw new RuntimeException(sprintf('No file exist with fixture data on "%s" path', $dataFile));
        }
        $dataList = Yaml::parse($dataFile);

        foreach ($dataList as $item) {
            $album = new Album();
            $album->setId($item['id'])
                  ->setTitle($item['title'])
                  ->setCreatedAt(new DateTime($item['created_at']))
                  ->setModifiedAt(new DateTime($item['modified_at']))
                  ->setAvailable($item['is_available']);
            if (!empty($item['weight'])) {
                $album->setWeight($item['weight']);
            }

            $manager->persist($album);
        }

        $manager->flush();
    }
}
