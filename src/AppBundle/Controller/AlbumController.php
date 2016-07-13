<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AlbumController
 *
 * This controller is responsible for REST API for objects of Album class.
 */

class AlbumController extends Controller
{
    /**
     * Get JSON serialized album object by id.
     *
     * @param int $id Album's id
     *
     * @return JsonResponse
     */
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

    /**
     * Get JSON serialized array of all album objects.
     *
     * @return JsonResponse
     */
    public function getAlbumsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $albums = $em
            ->getRepository('AppBundle:Album')
            ->findAllWithoutQueryingImages()
        ;

        return new JsonResponse($albums);
    }


    /**
     * Get JSON serialized array of album objects, which have $maxImagesCount images at max.
     *
     * @param int $maxImagesCount Max images count for album
     *
     * @return JsonResponse
     */
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
