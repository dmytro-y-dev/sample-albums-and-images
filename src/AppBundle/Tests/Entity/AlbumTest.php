<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumTest extends WebTestCase
{
    public function testGetId()
    {
        $album = new Album();

        $reflection = new \ReflectionClass($album);
        $reflection_property = $reflection->getProperty('id');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($album, 1);

        $this->assertEquals(1, $album->getId());
    }

    public function testSetName()
    {
        $album = new Album();

        $this->assertEquals($album, $album->setName('some name'));
    }

    public function testGetName()
    {
        $album = new Album();
        $album->setName('some name');

        $this->assertEquals('some name', $album->getName());
    }

    public function testAddImage()
    {
        $album = new Album();
        $image = new Image();

        $this->assertEquals($album, $album->addImage($image));
        $this->assertEquals(1, count($album->getImages()));
    }

    public function testRemoveImage()
    {
        $album = new Album();
        $image = new Image();

        $album->addImage($image);

        $this->assertEquals(1, count($album->getImages()));

        $album->removeImage($image);

        $this->assertEquals(0, count($album->getImages()));
    }

    public function testGetImages()
    {
        $album = new Album();
        $image1 = new Image();
        $image2 = new Image();

        $album->addImage($image1);
        $album->addImage($image2);

        $this->assertEquals(2, count($album->getImages()));
    }
}
