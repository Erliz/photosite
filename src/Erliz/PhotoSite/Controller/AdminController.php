<?php

/**
 * @author Stanislav Vetlovskiy
 * @date 25.11.2014
 */

namespace Erliz\PhotoSite\Controller;


use Symfony\Component\HttpFoundation\Request;

class AdminController extends SecurityAwareController
{
    public function indexAction()
    {

        return $this->renderView('Admin/index.twig');
    }

    public function loginAction(Request $request)
    {
        return $this->renderView('Admin/login.twig', array(
            'error' => $this->getLastError($request)
        ));
    }

    public function albumsIndexAction()
    {
        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity\Album');

        return $this->renderView('Admin/albums/index.twig', array(
            'albums' => $albumsRepository->findAll(),
            'menu' => array(
                'title' => 'Albums',
                'list' => array(
                    array('title' => 'Сортировка', 'link' => '/admin/albums/sort/', 'active'=>false),
                    array('title' => 'Видимость', 'link' => '/admin/albums/sort/', 'active'=>false)
                )
            )
        ));
    }

    public function albumsViewAction($id)
    {
        $em = $this->getEntityManager();
        $albumsRepository = $em->getRepository('Erliz\PhotoSite\Entity\Album');

        return $this->renderView('Admin/albums/view.twig', array(
            'album' => $albumsRepository->find($id)
        ));
    }
}
