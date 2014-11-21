<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Controller;


class AlbumsController extends ApplicationAwareController
{
    public function indexAction()
    {
        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity');

        return $this->renderView(
            'Albums/index.twig',
            array('albums' => $albumsRepository->findBy(array('is_available' => 1)))
        );
    }
} 