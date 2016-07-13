<?php

namespace AppBundle\Tests\FixtureImporter;

use AppBundle\Entity\Album;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\FixtureHandler\DefaultFixtureHandler;
use AppBundle\FixtureImporter\DefaultFixtureImporter;
use Doctrine\ORM\EntityManager;

class DefaultFixtureHandlerTest extends WebTestCase
{
    private $imageStoragePath = '../web/storage/images';
    private $fixturesPath = '';

    public function testReadFixtureJSON()
    {
        // Initialize SUT object $fixtureHandler and configure mock expectations

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureHandler = new DefaultFixtureHandler($em, $this->imageStoragePath, $this->fixturesPath);

        // Perform assertions

        $this->assertEmpty($fixtureHandler->readFixtureJSON());
    }

    public function testImportFixtureJSON()
    {
        // Initialize SUT object $fixtureHandler and configure mock expectations

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureImporter = $this->getMockBuilder(DefaultFixtureImporter::class)
            ->setMethods(array('loadAlbumsFromJSON', 'importAlbum'))
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureImporter->method('loadAlbumsFromJSON')->willReturn(array(new Album(), new Album()));

        $fixtureImporter->expects($this->once())
            ->method('loadAlbumsFromJSON')
            ->withAnyParameters();

        $fixtureImporter->expects($this->exactly(2))
            ->method('importAlbum')
            ->withAnyParameters();

        $fixtureHandler = new DefaultFixtureHandler($em, $this->imageStoragePath, $this->fixturesPath);

        // Execute expected code

        $fixtureHandler->importFixtureJSON($fixtureImporter, '{}');
    }

    public function testCleanDatabase()
    {
        // Initialize SUT object $fixtureHandler and configure mock expectations

        $em = $this->getMockBuilder(EntityManager::class)
            ->setMethods(array('createQuery'))
            ->disableOriginalConstructor()
            ->getMock();

        $em->method('createQuery')->willReturn(
            $this->getMockBuilder('stdClass')
                ->setMethods(array('getSingleResult'))
                ->getMock()
        );

        $em->expects($this->at(0))
            ->method('createQuery')
            ->with($this->equalTo('DELETE FROM AppBundle:Image'));

        $em->expects($this->at(1))
            ->method('createQuery')
            ->with($this->equalTo('DELETE FROM AppBundle:Album'));

        $fixtureHandler = new DefaultFixtureHandler($em, $this->imageStoragePath, $this->fixturesPath);

        // Execute expected code

        $fixtureHandler->cleanDatabase();
    }
}