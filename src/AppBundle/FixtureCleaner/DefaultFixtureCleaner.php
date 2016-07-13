<?php

namespace AppBundle\FixtureCleaner;

/**
 * Class DefaultFixtureCleaner
 *
 * This service is responsible for removal of imported fixtures from website.
 */

class DefaultFixtureCleaner
{
    private $em;
    private $imageStoragePath;

    /**
     * Default constructor.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param string $imageStoragePath Directory, where website's images storage is located
     */
    public function __construct($em, $imageStoragePath)
    {
        $this->em = $em;
        $this->imageStoragePath = $imageStoragePath;
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