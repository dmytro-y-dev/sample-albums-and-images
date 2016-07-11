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
class ImageController extends Controller
{
    private $serializer;

    public function __construct()
    {
        $encoders = array(new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/api/albums/{albumId}/page/{pageId}", name="app_images_paginated")
     */
    public function getPaginatedImagesAction($albumId, $pageId)
    {
        $em = $this->getDoctrine()->getManager();

        $imagesQuery = $em
            ->getRepository('AppBundle:Image')
            ->queryAllByAlbum($albumId)
        ;

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $imagesQuery,
            !empty($pageId) ? $pageId : 1,
            10
        );

        $jsonContent = $this->serializer->serialize($pagination, 'json');

        return new Response($jsonContent);
    }
}
