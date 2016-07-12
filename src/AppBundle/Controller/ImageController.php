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
use FOS\RestBundle\Controller\Annotations\NamePrefix;

/**
 * @Prefix("api")
 * @NamePrefix("app_api_")
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

    public function getPaginatedImagesAction($albumId, $pageId)
    {
        $maxImagesPerPage = $this->getParameter('app.max_images_per_page');

        $em = $this->getDoctrine()->getManager();

        $imagesQuery = $em
            ->getRepository('AppBundle:Image')
            ->queryAllByAlbum($albumId)
        ;

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $imagesQuery,
            !empty($pageId) ? $pageId : 1,
            $maxImagesPerPage
        );

        $paginationHTML = $this->render(
            'AppBundle:default:pagination.html.twig',
            array_merge(
                $pagination->getPaginationData(), array(
                    'route' => 'app_frontend_home',
                    'albumId' => $albumId
                )
            )
        )->getContent();

        $imagesPage = array(
            'pagination' => $paginationHTML,
            'images' => $pagination->getItems()
        );

        $jsonContent = $this->serializer->serialize($imagesPage, 'json');

        return new Response($jsonContent);
    }
}
