<?php

namespace AppBundle\FixtureImporter;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;
use AppBundle\FilenameGenerator\ImageFilenameGenerator;

class AlbumFixtureImporter
{
    private $em;
    private $imageStoragePath;
    private $imageFixtureFilesPath;

    public function __construct($em, $imageStoragePath, $imageFixtureFilesPath)
    {
        $this->em = $em;
        $this->imageStoragePath = $imageStoragePath;
        $this->imageFixtureFilesPath = $imageFixtureFilesPath;
    }

    public function loadAlbumsFromJSON($albumsJSON)
    {
        $albumsArray = @json_decode($albumsJSON, true);

        if (empty($albumsArray)) {
            return array();
        }

        $albums = array();

        foreach ($albumsArray as $albumArray) {
            $album = new Album();
            $album->setName($albumArray['name']);
            $album = $this->addImagesToAlbumObject($album, $albumArray['images']);

            $albums[] = $album;
        }

        return $albums;
    }

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

    public function importAlbum(\AppBundle\Entity\Album $album)
    {
        foreach ($album->getImages() as $image) {
            $newFilename = $this->copyImageFileToStorage($image);
            $image->setFilename($newFilename);
        }

        $this->em->persist($album);
        $this->em->flush();
    }

    public function copyImageFileToStorage(\AppBundle\Entity\Image $image)
    {
        $originalFilename = substr($image->getFilename(), 0, strpos($image->getFilename(), '.')); // part of filename before .
        $originalExtension = substr($image->getFilename(), strpos($image->getFilename(), '.'));  // part of filename after .

        $newFilename = ImageFilenameGenerator::generate($originalFilename, $originalExtension, $image);  // full filename with extension

        $image->setFilename($newFilename);

        copy(
            $this->imageFixtureFilesPath.'/'.$originalFilename.$originalExtension,
            $this->imageStoragePath.'/'.$newFilename
        );

        return $newFilename;
    }
}