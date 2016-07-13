<?php

namespace AppBundle\FixtureHandler;

/**
 * Class DefaultFixtureHandler
 *
 * This service is responsible for locating and reading fixtures and for removal of imported fixtures from website.
 */

class DefaultFixtureHandler
{
    private $em;
    private $fixturesPath;
    private $imageStoragePath;

    /**
     * Default constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param string $imageStoragePath Directory, where website's images storage is located
     * @param string $fixturesPath Directory, where fixtures are stored
     */
    public function __construct($em, $imageStoragePath, $fixturesPath)
    {
        $this->em = $em;
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