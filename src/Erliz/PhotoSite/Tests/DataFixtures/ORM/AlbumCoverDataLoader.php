<?php

/**
 * @author Stanislav Vetlovskiy
 * @date   21.11.2014
 */

namespace Erliz\AlbumSite\Tests\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

class AlbumCoverDataLoader implements FixtureInterface
{
    public function getDependencies()
    {
        return array('PhotoDataLoader');
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
        $dataFile = __DIR__ . '/dump/Album.yml';
        if (!file_exists($dataFile)) {
            throw new RuntimeException(sprintf('No file exist with fixture data on "%s" path', $dataFile));
        }
        $dataList = Yaml::parse($dataFile);

        foreach ($dataList as $item) {
            if (!$item['cover']) {
                continue;
            }

            $album = $manager->find('Erliz\PhotoSite\Entity\Album', $item['id']);
            $album->setCover($manager->find('Erliz\PhotoSite\Entity\Photo', $item['cover']));

            $manager->persist($album);
        }

        $manager->flush();
    }
}
