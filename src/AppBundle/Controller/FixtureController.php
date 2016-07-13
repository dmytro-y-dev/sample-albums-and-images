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
        $fixtureHandler = $this->getFixtureHandler();

        $fixtureHandler->importFixtureJSON(
            $fixtureHandler->readFixtureJSON()
        );

        return new Response('done.');
    }

    /**
     * Remove all fixtures from website.
     *
     * @return Response
     */
    public function truncateDatabaseAction()
    {
        $fixtureHandler = $this->getFixtureHandler();

        $fixtureHandler->cleanDatabase();
        $fixtureHandler->cleanFilesStorage();

        return new Response('done.');
    }

    /*
     * Get DefaultFixtureHandler service.
     *
     * @return \AppBundle\FixtureHandler\DefaultFixtureHandler
     */
    public function getFixtureHandler()
    {
        return $this->get('app.default_fixture_handler');
    }
}
