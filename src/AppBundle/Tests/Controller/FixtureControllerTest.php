<?php

namespace AppBundle\Tests\Controller;

use AppBundle\FixtureCleaner\DefaultFixtureCleaner;
use AppBundle\FixtureImporter\DefaultFixtureImporter;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Controller\FixtureController;

class FixtureControllerTest extends WebTestCase
{
    private $imageStoragePath = '';
    private $fixturesPath = '';

    public function testImportFixture()
    {
        // Initialize SUT object $controller and configure mock expectations

        $fixtureImporter = $this->getMockBuilder(DefaultFixtureImporter::class)
            ->setMethods(array('importAlbums'))
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureImporter->expects($this->once())
            ->method('importAlbums')
            ->withAnyParameters();

        $controller = $this->getMockBuilder(FixtureController::class)
            ->setMethods(array('get'))
            ->getMock();

        $controller->method('get')->willReturn($fixtureImporter);

        $controller->expects($this->once())
            ->method('get')
            ->with('app.default_fixture_importer');

        // Execute expected code

        $response = $controller->importFixtureAction();

        $this->assertEquals('done.', $response->getContent());
    }

    public function testTruncateDatabase()
    {
        // Initialize SUT object $controller and configure mock expectations

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureCleaner = $this->getMockBuilder(DefaultFixtureCleaner::class)
            ->setMethods(array('cleanDatabase', 'cleanFilesStorage'))
            ->setConstructorArgs(array($em, $this->imageStoragePath, $this->fixturesPath))
            ->getMock();

        $fixtureCleaner->expects($this->once())
            ->method('cleanDatabase')
            ->withAnyParameters();

        $fixtureCleaner->expects($this->once())
            ->method('cleanFilesStorage')
            ->withAnyParameters();

        $controller = $this->getMockBuilder(FixtureController::class)
            ->setMethods(array('get'))
            ->getMock();

        $controller->method('get')->willReturn($fixtureCleaner);

        $controller->expects($this->once())
            ->method('get')
            ->with('app.default_fixture_cleaner');

        // Execute expected code

        $response = $controller->truncateDatabaseAction();

        $this->assertEquals('done.', $response->getContent());
    }
}
