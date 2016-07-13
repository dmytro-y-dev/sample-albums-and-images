<?php

namespace AppBundle\Controller;

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
        $fixturesPath = $this->getParameter('app.fixtures_path');

        $fixtureJSONPath = "{$fixturesPath}/json/albums.json";
        $fixtureJSON = file_get_contents($fixtureJSONPath);

        $albumFixtureImporter = $this->get("app.album_fixture_importer");

        $albums = $albumFixtureImporter->loadAlbumsFromJSON($fixtureJSON);

        foreach ($albums as $album) {
            $albumFixtureImporter->importAlbum($album);
        }

        return new Response('done.');
    }

    public function truncateDatabaseAction(Request $request)
    {
        $storagePath = $this->getParameter('stof_doctrine_extensions.default_file_path');

        $em = $this->getDoctrine()->getManager();

        $em->createQuery('DELETE FROM AppBundle:Image')
            ->getSingleResult();
        $em->createQuery('DELETE FROM AppBundle:Album')
            ->getSingleResult();

        foreach(glob("{$storagePath}/images/*") as $file) {
            unlink($file);
        }

        return new Response('done.');
    }
}
