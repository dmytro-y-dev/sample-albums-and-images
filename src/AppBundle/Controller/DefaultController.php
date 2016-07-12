<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle:default:index.html.twig');
    }

    public function importFixtureAction(Request $request)
    {
        $albumFixtureImporter = $this->get("app.album_fixture_importer");

        $fixtureJSONPath = "../contrib/fixture/json/albums.json";
        $fixtureJSON = file_get_contents($fixtureJSONPath);

        $albums = $albumFixtureImporter->loadAlbumsFromJSON($fixtureJSON);

        foreach ($albums as $album) {
            $albumFixtureImporter->importAlbum($album);
        }

        return new Response('done.');
    }

    public function truncateDatabaseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $em->createQuery('DELETE i FROM AppBundle:Image i')
            ->getSingleResult();
        $em->createQuery('DELETE a FROM AppBundle:Album a')
            ->getSingleResult();

        foreach(glob("../web/storage/images/*") as $file) {
            unlink($file);
        }

        return new Response('done.');
    }
}
