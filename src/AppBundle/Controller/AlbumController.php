<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AlbumController extends Controller
{
    public function getAlbumAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $album = $em
            ->getRepository('AppBundle:Album')
            ->findOneWithoutQueryingImages($id)
        ;

        if (empty($album)) {
            return new Response('{}');
        }

        $album['images'] = $em
            ->getRepository('AppBundle:Image')
            ->queryAllByAlbum($id)
            ->getResult()
        ;

        return new JsonResponse($album);
    }

    public function getAlbumsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $albums = $em
            ->getRepository('AppBundle:Album')
            ->findAllWithoutQueryingImages()
        ;

        return new JsonResponse($albums);
    }

    public function getAlbumsWithMaxImagesAction($maxImagesCount)
    {
        $em = $this->getDoctrine()->getManager();

        $albums = $em
            ->getRepository('AppBundle:Album')
            ->findAllWithMaxImages($maxImagesCount)
        ;

        return new JsonResponse($albums);
    }
}
