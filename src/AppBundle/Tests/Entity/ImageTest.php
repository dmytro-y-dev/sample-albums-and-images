<?php

namespace AppBundle\Tests\Entity;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageTest extends WebTestCase
{
    public function testGetId()
    {
        $image = new Image();

        $reflection = new \ReflectionClass($image);
        $reflection_property = $reflection->getProperty('id');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($image, 1);

        $this->assertEquals(1, $image->getId());
    }

    public function testSetFilename()
    {
        $image = new Image();

        $this->assertEquals($image, $image->setFilename('some name'));
    }

    public function testGetFilename()
    {
        $image = new Image();

        $image->setFilename('some name');

        $this->assertEquals('some name', $image->getFilename());
    }

    public function testSetMimetype()
    {
        $image = new Image();

        $this->assertEquals($image, $image->setMimetype('some name'));
    }

    public function testGetMimetype()
    {
        $image = new Image();

        $image->setMimetype('some name');

        $this->assertEquals('some name', $image->getMimetype());
    }

    public function testSetDescription()
    {
        $image = new Image();

        $this->assertEquals($image, $image->setDescription('some name'));
    }

    public function testGetDescription()
    {
        $image = new Image();

        $image->setDescription('some name');

        $this->assertEquals('some name', $image->getDescription());
    }

    public function testSetAlbum()
    {
        $image = new Image();
        $album = new Album();

        $this->assertEquals($image, $image->setAlbum($album));
    }

    public function testGetAlbum()
    {
        $image = new Image();
        $album = new Album();

        $image->setAlbum($album);

        $this->assertEquals($album, $image->getAlbum());
    }

    public function testSetCreated()
    {
        $image = new Image();

        $this->assertEquals($image, $image->setCreated(new \DateTime()));
    }

    public function testGetCreated()
    {
        $image = new Image();
        $datetime = new \DateTime();

        $image->setCreated($datetime);

        $this->assertEquals($datetime, $image->getCreated());
    }
}
