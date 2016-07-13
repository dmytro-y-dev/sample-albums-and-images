<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FixtureController
 *
 * This controller is responsible for fixture related commands.
 */

class FixtureController extends Controller
{
    /**
     * Import fixtures onto website.
     *
     * @return Response
     */
    public function importFixtureAction()
    {
        $this
            ->get('app.default_fixture_importer')
            ->importAlbums()
        ;

        return new Response('done.');
    }

    /**
     * Remove all fixtures from website.
     *
     * @return Response
     */
    public function truncateDatabaseAction()
    {
        $fixtureCleaner =  $this->get('app.default_fixture_cleaner');

        $fixtureCleaner->cleanDatabase();
        $fixtureCleaner->cleanFilesStorage();

        return new Response('done.');
    }
}
