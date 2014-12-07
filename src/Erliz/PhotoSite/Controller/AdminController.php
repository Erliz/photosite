<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 25.11.2014
 */

namespace Erliz\PhotoSite\Controller;


use Erliz\PhotoSite\Entity\Album;
use Erliz\PhotoSite\Entity\Photo;
use Erliz\PhotoSite\Extension\JqueryFileUpload\JqueryFileUploadExtension;
use Erliz\PhotoSite\Service\JqueryFileUploadService;
use Erliz\PhotoSite\Service\PhotoService;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminController extends SecurityAwareController
{
    public function indexAction()
    {

        return $this->renderView('Admin/index.twig');
    }

    public function loginAction(Request $request)
    {
        return $this->renderView(
            'Admin/login.twig',
            array(
                'error' => $this->getLastError($request)
            )
        );
    }

    public function albumsIndexAction()
    {
        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity\Album');

        return $this->renderView(
            'Admin/albums/index.twig',
            array(
                'albums' => $albumsRepository->findBy(array(), array('weight' => 'asc', 'title' => 'asc')),
            )
        );
    }

    public function albumCreateAction()
    {
        return $this->renderView('Admin/albums/create.twig');
    }

    public function uploadAction()
    {
        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity\Album');

        return $this->renderView(
            'Admin/upload.twig',
            array(
                'albums' => $albumsRepository->findBy(array(), array('weight' => 'asc', 'title' => 'asc'))
            )
        );
    }

    public function photoAction(Request $request)
    {
        $albumId = $request->get('id');
        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity\Album');
        $album = $albumsRepository->find($albumId);
        if (!$album) {
            return new NotFoundHttpException(sprintf('Not found album with id "%d"', $albumId));
        }

        return $this->renderView(
            'Admin/albums/photo.twig',
            array(
                'album' => $album,
                'albums' => $albumsRepository->findBy(array(), array('title' => 'asc'))
            )
        );
    }

    public function photoUploadAction(Request $request)
    {
        $albumId = $request->get('id');
        $albumsRepository = $this->getEntityManager()->getRepository('Erliz\PhotoSite\Entity\Album');
        $album = $albumsRepository->find($albumId);
        if (!$album) {
            return new NotFoundHttpException(sprintf('Not found album with id "%d"', $albumId));
        }
        $photoService = $this->getApp()['photo.service'];
        $files = $photoService->upload($album);

        return new JsonResponse(array('files' => $files));
    }

    public function photoSaveWeightAction(Request $request)
    {
        $albumId = $request->get('id');
        $photoOrder = $request->get('photo');
        $albumsRepository = $this->getEntityManager()->getRepository('Erliz\PhotoSite\Entity\Album');
        /** @var Album $album */
        $album = $albumsRepository->find($albumId);
        if (!$album) {
            return new NotFoundHttpException(sprintf('Not found album with id "%d"', $albumId));
        }
        /** @var PhotoService $photoService */
        $photoService = $this->getApp()['photo.service'];
        $photoService->setWight($album->getPhotos(), $photoOrder);
        $this->getEntityManager()->flush();

        return new JsonResponse(array('status' => 'success'));
    }

    public function photoChangeAlbumAction(Request $request)
    {
        $albumId = $request->get('album_id');
        $photoId = $request->get('photo_id');
        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity\Album');
        $photoRepository = $em->getRepository('Erliz\PhotoSite\Entity\Photo');
        /** @var Album $album */
        $album = $albumsRepository->find($albumId);
        if (!$album) {
            return new NotFoundHttpException(sprintf('Not found album with id "%d"', $albumId));
        }
        /** @var Photo $photo */
        $photo = $photoRepository->find($photoId);
        if (!$photo) {
            return new NotFoundHttpException(sprintf('Not found photo with id "%d"', $photoId));
        }

        $photo->setAlbum($album);
        $em->flush();

        return new JsonResponse(array('status' => 'success'));
    }

    public function albumEditAction($id)
    {
        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity\Album');

        return $this->renderView(
            'Admin/albums/edit.twig',
            array(
                'album' => $albumsRepository->find($id)
            )
        );
    }

    public function albumsSaveAction(Request $request)
    {
        $albumsData = $request->get('albums');

        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity\Album');
        $photoRepository = $em->getRepository('Erliz\PhotoSite\Entity\Photo');
        $updatedAlbums = array();

        foreach ($albumsData as $albumData) {
            foreach($albumData as $key => $val) {
                $albumData['key'] = trim(strip_tags($val));
            }
            /** @var Album $album */
            if (empty($albumData['id'])) {
                if (empty($albumData['title'])) {
                    throw new InvalidArgumentException('Could not create album, "title" param is empty');
                }
                $album = new Album();
                $em->persist($album);
            } else {
                $album = $albumsRepository->find($albumData['id']);
            }

            $album->setTitle($albumData['title']);
            if (!empty($albumData['is_available'])) {
                $album->setAvailable($albumData['is_available']);
            } else {
                $album->setAvailable(false);
            }
            if (!empty($albumData['description'])) {
                $album->setDescription($albumData['description']);
            }
            if (!empty($albumData['weight'])) {
                $album->setWeight($albumData['weight']);
            }

            if (!empty($albumData['cover'])) {
                /** @var Photo $cover */
                $cover = $photoRepository->find($albumData['cover']);
                if ($cover) {
                    $album->setCover($cover);
                }
            }

            $updatedAlbums []= $album;
        }
            $em->flush();

        if  ($request->isXmlHttpRequest()) {
            return new JsonResponse(array('data' => $updatedAlbums));
        }

        return $this->getApp()->redirect($this->getApp()['url_generator']->generate('erliz_photosite_admin_albums'));
    }
}
