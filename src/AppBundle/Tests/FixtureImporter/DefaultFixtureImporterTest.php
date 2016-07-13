<?php

namespace AppBundle\Tests\FixtureImporter;

use AppBundle\Entity\Album;
use AppBundle\Entity\Image;
use AppBundle\FilenameGenerator\ImageFilenameGenerator;

use AppBundle\FixtureImporter\DefaultFixtureImporter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultFixtureImporterTest extends WebTestCase
{
    private $imageStoragePath = '';
    private $fixturesPath = '';

    public function testImportFixtureFromJSON()
    {
        // Initialize SUT object $fixtureImporter and configure mock expectations

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureImporter = $this->getMockBuilder(DefaultFixtureImporter::class)
            ->setMethods(array('deserializeAlbums', 'importAlbum', 'readAlbumsJSON'))
            ->setConstructorArgs(array($em, $this->imageStoragePath, $this->fixturesPath))
            ->getMock();

        $fixtureImporter->method('deserializeAlbums')->willReturn(array(new Album(), new Album()));

        $fixtureImporter->expects($this->once())
            ->method('readAlbumsJSON')
            ->withAnyParameters();

        $fixtureImporter->expects($this->once())
            ->method('deserializeAlbums')
            ->withAnyParameters();

        $fixtureImporter->expects($this->exactly(2))
            ->method('importAlbum')
            ->withAnyParameters();

        // Execute expected code

        $fixtureImporter->importAlbums();
    }

    public function testReadAlbumsJSON()
    {
        // Initialize SUT object $fixtureImporter and configure mock expectations

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureImporter = new DefaultFixtureImporter($em, $this->imageStoragePath, $this->fixturesPath);

        // Perform assertions

        $this->assertEmpty($fixtureImporter->readAlbumsJSON());
    }

    public function testDeserializeAlbumsGood()
    {
        // Initialize SUT object $fixtureImporter

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureImporter = new DefaultFixtureImporter($em, $this->imageStoragePath, $this->fixturesPath);

        // Perform assertions

        $testJSONFragment = <<<JSON_FRAGMENT
[{"images": [{"mimetype": "image/jpeg", "description": "JavaScript be and in possible The", "id": "1", "filename": "1.jpg"}, {"mimetype": "image/jpeg", "description": "for can difficult tries a compiler for though a As language true result a statements in below", "id": "2", "filename": "2.jpg"}, {"mimetype": "image/jpeg", "description": "down variable", "id": "3", "filename": "3.jpg"}, {"mimetype": "image/jpeg", "description": "things As fails", "id": "4", "filename": "4.jpg"}, {"mimetype": "image/jpeg", "description": "analogous might little CoffeeScript directly that be much You otherwise functions string", "id": "5", "filename": "5.jpg"}], "id": "1", "name": "Album 1"}, {"images": [{"mimetype": "image/jpeg", "description": "would you all undefined zero be to check Watch you in", "id": "6", "filename": "6.jpg"}, {"mimetype": "image/jpeg", "description": "gets for Watch", "id": "7", "filename": "7.jpg"}, {"mimetype": "image/jpeg", "description": "all much variablecomes final safer expression functions Ruby's functions conditional JavaScript than can statement lets into", "id": "8", "filename": "8.jpg"}, {"mimetype": "image/jpeg", "description": "the a be analogous least all be below CoffeeScript analogous a to", "id": "9", "filename": "9.jpg"}, {"mimetype": "image/jpeg", "description": "compiler into value for that try/catch expressions be existential", "id": "10", "filename": "10.jpg"}, {"mimetype": "image/jpeg", "description": "as or function sure", "id": "11", "filename": "11.jpg"}, {"mimetype": "image/jpeg", "description": "unless useful Everything strings make or are variable in passing", "id": "12", "filename": "12.jpg"}, {"mimetype": "image/jpeg", "description": "be into the function existence though like things statements be a", "id": "13", "filename": "13.jpg"}, {"mimetype": "image/jpeg", "description": "into even that Things compiler expression strings result do things much unless down the it string return expression is", "id": "29", "filename": "29.jpg"}], "id": "2", "name": "Album 2"}]
JSON_FRAGMENT;

        $albums = $fixtureImporter->deserializeAlbums($testJSONFragment);

        $this->assertEquals(2, count($albums));
        $this->assertEquals('Album 1', $albums[0]->getName());
    }

    public function testDeserializeAlbumsBad()
    {
        // Initialize SUT object $fixtureImporter

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureImporter = new DefaultFixtureImporter($em, $this->imageStoragePath, $this->fixturesPath);

        // Perform assertions

        $this->assertEmpty($fixtureImporter->deserializeAlbums(''));
    }

    public function testAddImagesToAlbumObject()
    {
        // Initialize SUT object $fixtureImporter

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureImporter = new DefaultFixtureImporter($em, $this->imageStoragePath, $this->fixturesPath);

        // Execute expected code

        $images = array(
            array(
                'filename' => 'x.jpg',
                'mimetype' => 'jpeg',
                'description' => 'ok1',
            ),
            array(
                'filename' => 'y.jpg',
                'mimetype' => 'jpeg',
                'description' => 'ok2',
            ),
        );

        $album = $this->getMockBuilder('AppBundle\Entity\Album')
            ->setMethods(array('addImage'))
            ->disableOriginalConstructor()
            ->getMock();

        $album->expects($this->exactly(2))
            ->method('addImage')
            ->withAnyParameters();

        $fixtureImporter->addImagesToAlbumObject($album, $images);
    }

    public function testImportAlbum()
    {
        // Initialize SUT object $fixtureImporter and configure mock expectations

        $em = $this->getMockBuilder(EntityManager::class)
            ->setMethods(array('persist', 'flush'))
            ->disableOriginalConstructor()
            ->getMock();

        $em->expects($this->once())
            ->method('persist')
            ->withAnyParameters();

        $em->expects($this->once())
            ->method('flush')
            ->withAnyParameters();

        $fixtureImporter = new DefaultFixtureImporter($em, $this->imageStoragePath, $this->fixturesPath);

        // Execute expected code

        $album = new Album();
        $image = new Image();
        $image->setCreated(new \DateTime());
        $image->setAlbum($album);
        $album->addImage($image);

        $fixtureImporter->importAlbum($album);
    }
}