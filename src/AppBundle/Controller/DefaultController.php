<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:default:index.html.twig');
    }

    /**
     * @Route("/import-fixture", name="import_fixture")
     */
    public function importFixtureAction(Request $request)
    {
        $fixtureJSONPath = "../src/AppBundle/Resources/fixture/json/albums.json";
        $fixtureJSON = file_get_contents($fixtureJSONPath);

        $albumFixtureImporter = $this->get("app.album_fixture_importer");
        $albums = $albumFixtureImporter->loadAlbumsFromJSON($fixtureJSON);

        foreach ($albums as $album) {
            $albumFixtureImporter->importAlbum($album);
        }

        return new Response('done.');
    }
}
