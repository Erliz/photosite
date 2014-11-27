<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 21.11.2014
 */

namespace Erliz\PhotoSite\Controller;


use Erliz\PhotoSite\Entity\Album;
use Erliz\PhotoSite\Entity\Photo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AlbumsController extends ApplicationAwareController
{
    public function indexAction(Request $request)
    {
        $page = $request->query->get('page') ? : 1;

        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity\Album');

        return $this->renderView(
            'Albums/index.twig',
            array(
                'albums' => $albumsRepository->findBy(
                    array('isAvailable' => 1),
                    array('weight' => 'asc', 'title' => 'asc')
                ),
                'page' => $page
            )
        );
    }

    public function viewAction(Request $request)
    {
        $id = $request->get('id');
        $page = $request->get('page') ? : 0;

        $em = $this->getEntityManager();
        /** @var Album $album */
        $album = $em->find('Erliz\PhotoSite\Entity\Album', $id);
        if (empty($album) || !$album->isAvailable()) {
            return new NotFoundHttpException();
        }

        return $this->renderView(
            'Albums/view.twig',
            array('album' => $album, 'page' => $page)
        );
    }

    public function viewJsonAction(Request $request)
    {
        $id = $request->get('id');

        $em = $this->getEntityManager();
        /** @var Album $album */
        $album = $em->find('Erliz\PhotoSite\Entity\Album', $id);
        if (empty($album) || !$album->isAvailable()) {
            return new JsonResponse(array('error' => 'album not found', 'data' => null), 404);
        }
        $photos = array();
        /** @var Photo $photo */
        foreach ($album->getPhotos() as $photo) {
            $photos [] = $photo->toArray();
        }
        $data = array('data' => $album->toArray());
        $data['data']['photos'] = $photos;

        return new JsonResponse($data);
    }
} 