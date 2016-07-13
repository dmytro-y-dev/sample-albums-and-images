<?php

namespace AppBundle\FixtureImporter;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;
use AppBundle\FilenameGenerator\ImageFilenameGenerator;

/**
 * Class DefaultFixtureImporter
 *
 * This service is responsible for parsing JSON serialized fixtures and importing them into application.
 */

class DefaultFixtureImporter
{
    private $em;
    private $imageStoragePath;
    private $fixturesPath;

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
     * Read JSON string, which contains serialized albums, and convert it
     * to array of Album objects.
     *
     * @param string $albumsJSON JSON serialized albums
     *
     * @return array
     */
    public function loadAlbumsFromJSON($albumsJSON)
    {
        // Convert JSON string to array

        $albumsArray = @json_decode($albumsJSON, true);

        if (empty($albumsArray)) {
            return array();
        }

        // Convert array of Album arrays to array of Album objects

        $albums = array();

        foreach ($albumsArray as $albumArray) {
            $album = new Album();
            $album->setName($albumArray['name']);
            $album = $this->addImagesToAlbumObject($album, $albumArray['images']);

            $albums[] = $album;
        }

        return $albums;
    }

    /**
     * Convert $imagesArray (which is array of Image arrays) to array of Image objects and
     * add resulting objects array to parent album $album.
     *
     * @param \AppBundle\Entity\Album $album Parent album
     * @param array $imagesArray Album's images
     *
     * @return Album
     */
    public function addImagesToAlbumObject(\AppBundle\Entity\Album $album, $imagesArray)
    {
        foreach ($imagesArray as $imageArray) {
            $image = new Image();
            $image->setFilename($imageArray['filename']);
            $image->setMimetype($imageArray['mimetype']);
            $image->setDescription($imageArray['description']);
            $image->setCreated(new \DateTime());
            $image->setAlbum($album);

            $album->addImage($image);
        }

        return $album;
    }

    /**
     * Add album and its images onto website.
     *
     * @param \AppBundle\Entity\Album $album
     */
    public function importAlbum(\AppBundle\Entity\Album $album)
    {
        // Copy all images from fixture directory to website's images storage.

        foreach ($album->getImages() as $image) {
            $newFilename = $this->copyImageFileToStorage($image);
            $image->setFilename($newFilename);
        }

        // Insert album and its images into database.

        $this->em->persist($album);
        $this->em->flush();
    }

    /**
     * Copy image file from fixture storage to website's images storage.
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return string image's filename in website's storage
     */
    public function copyImageFileToStorage(\AppBundle\Entity\Image $image)
    {
        $originalFilename = $image->getFilename();

        $originalFilenameWithoutExtension = substr(
            $image->getFilename(), 0, strpos($image->getFilename(), '.')
        ); // part of filename before .

        $originalExtension = substr(
            $image->getFilename(), strpos($image->getFilename(), '.')
        ); // part of filename after . including dot symbol

        $newFilename = ImageFilenameGenerator::generate(
            $originalFilenameWithoutExtension, $originalExtension, $image
        ); // newly generated full filename with extension

        @copy(
            $this->fixturesPath.'/images/'.$originalFilename,
            $this->imageStoragePath.'/'.$newFilename
        );

        $image->setFilename($newFilename);

        return $newFilename;
    }
}