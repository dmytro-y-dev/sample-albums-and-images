<?php

namespace AppBundle\FixtureHandler;

/**
 * Class DefaultFixtureHandler
 *
 * This service is responsible for locating and loading fixtures on website, which DefaultFixtureImporter service uses
 * for importing, and for removal of fixtures data from website.
 */

class DefaultFixtureHandler
{
    private $em;
    private $fixtureImporter;
    private $fixturesPath;
    private $imageStoragePath;

    /**
     * Default constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param \AppBundle\FixtureImporter\DefaultFixtureImporter $fixtureImporter Object to handle fixtures import
     * @param string $imageStoragePath Directory, where website's images storage is located
     * @param string $fixturesPath Directory, where fixtures are stored
     */
    public function __construct($em, $fixtureImporter, $imageStoragePath, $fixturesPath)
    {
        $this->em = $em;
        $this->fixtureImporter = $fixtureImporter;
        $this->imageStoragePath = $imageStoragePath;
        $this->fixturesPath = $fixturesPath;
    }

    /**
     * Read JSON string with fixtures data.
     *
     * @return string
     */
    public function readFixtureJSON()
    {
        $fixtureJSONPath = "{$this->fixturesPath}/json/albums.json";
        $fixtureJSON = @file_get_contents($fixtureJSONPath);

        return $fixtureJSON;
    }

    /**
     * Import fixtures data from JSON string.
     *
     * @param string $fixtureJSON Albums fixtures as JSON string
     */
    public function importFixtureJSON($fixtureJSON)
    {
        $albums = $this->fixtureImporter->loadAlbumsFromJSON($fixtureJSON);

        foreach ($albums as $album) {
            $this->fixtureImporter->importAlbum($album);
        }
    }

    /**
     * Remove all images and albums from database.
     */
    public function cleanDatabase()
    {
        $this->em->createQuery('DELETE FROM AppBundle:Image')->getSingleResult();
        $this->em->createQuery('DELETE FROM AppBundle:Album')->getSingleResult();
    }

    /**
     * Remove all images from files storage.
     */
    public function cleanFilesStorage()
    {
        foreach(glob("{$this->imageStoragePath}/*") as $file) {
            unlink($file);
        }
    }
}