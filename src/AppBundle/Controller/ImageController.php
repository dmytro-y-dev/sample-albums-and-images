<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ImageController
 *
 * This controller is responsible for REST API for objects of Image class.
 */

class ImageController extends Controller
{
    /**
     * Get JSON serialized array of images with pagination control.
     *
     * Result array contains two values:
     * 1. 'pagination' contains Html code for pager UI control.
     * 2. 'images' contains JSON serialized array of images for current page.
     *
     * @param int $albumId Album's id
     * @param int $pageId Page index
     *
     * @return JsonResponse
     */
    public function getPaginatedImagesAction($albumId, $pageId)
    {
        $maxImagesPerPage = $this->getParameter('app.max_images_per_page');

        $em = $this->getDoctrine()->getManager();

        $imagesQuery = $em
            ->getRepository('AppBundle:Image')
            ->queryAllByAlbum($albumId)
        ;

        // Paginate images

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $imagesQuery,
            !empty($pageId) ? $pageId : 1,
            $maxImagesPerPage
        );

        // Render pager control and keep resulted Html

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

        // Return rendered pager control and images on current page

        return new JsonResponse(array(
            'pagination' => $paginationHtml,
            'images' => $pagination->getItems()
        ));
    }
}
