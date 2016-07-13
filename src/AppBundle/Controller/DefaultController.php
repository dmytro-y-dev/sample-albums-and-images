<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 *
 * This controller is responsible for public pages.
 */

class DefaultController extends Controller
{
    /**
     * Get application main page.
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('AppBundle:default:index.html.twig');
    }
}
