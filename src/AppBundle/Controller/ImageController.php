<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ImageController extends Controller
{
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

        $paginationHtml = $this->render(
            $pagination->getTemplate(),
            array_merge(
                $pagination->getPaginationData(),
                array(
                    'route' => 'app_frontend_paginated_images',
                    'query' => array(
                        'id' => $albumId,
                        'page' => $pageId,
                    ),
                    'pageParameterName' => 'page'
                )
            )
        )->getContent();

        return new JsonResponse(array(
            'pagination' => $paginationHtml,
            'images' => $pagination->getItems()
        ));
    }
}
