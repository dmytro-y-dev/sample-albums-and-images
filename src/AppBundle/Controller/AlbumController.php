<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use FOS\RestBundle\Controller\Annotations\Prefix;

/**
 * @Prefix("api")
 */
class AlbumController extends Controller
{
    private $serializer;

    public function __construct()
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $this->serializer = new Serializer($normalizers, $encoders);
    }

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

        $jsonContent = $this->serializer->serialize($album, 'json');

        return new Response($jsonContent);
    }

    public function getAlbumsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $album = $em
            ->getRepository('AppBundle:Album')
            ->findAllWithoutQueryingImages()
        ;

        $jsonContent = $this->serializer->serialize($album, 'json');

        return new Response($jsonContent);
    }

    /**
     * @Route("/api/albums/filter-max-images/{maxImagesCount}", name="app_albums_with_max_images")
     */
    public function getAlbumsWithMaxImagesAction($maxImagesCount)
    {
        $em = $this->getDoctrine()->getManager();

        $albums = $em
            ->getRepository('AppBundle:Album')
            ->findAllWithMaxImages($maxImagesCount)
        ;

        $jsonContent = $this->serializer->serialize($albums, 'json');

        return new Response($jsonContent);
    }
}
