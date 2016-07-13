<?php

namespace AppBundle\Tests\Controller;

use AppBundle\FixtureHandler\DefaultFixtureHandler;
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

        $em = $this->getMockBuilder(EntityManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $fixtureHandler = $this->getMockBuilder(DefaultFixtureHandler::class)
            ->setMethods(array('readFixtureJSON'))
            ->setConstructorArgs(array($em, $this->imageStoragePath, $this->fixturesPath))
            ->getMock();

        $fixtureHandler->method('readFixtureJSON')->willReturn('{}');

        $fixtureHandler->expects($this->once())
            ->method('readFixtureJSON')
            ->withAnyParameters();

        $fixtureImporter = $this->getMockBuilder(DefaultFixtureImporter::class)
            ->disableOriginalConstructor()
            ->getMock();

        $controller = $this->getMockBuilder(FixtureController::class)
            ->setMethods(array('getFixtureHandler', 'get'))
            ->getMock();

        $controller->method('getFixtureHandler')->willReturn($fixtureHandler);
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

        $fixtureHandler = $this->getMockBuilder(DefaultFixtureHandler::class)
            ->setMethods(array('cleanDatabase', 'cleanFilesStorage'))
            ->setConstructorArgs(array($em, $this->imageStoragePath, $this->fixturesPath))
            ->getMock();

        $fixtureHandler->expects($this->once())
            ->method('cleanDatabase')
            ->withAnyParameters();

        $fixtureHandler->expects($this->once())
            ->method('cleanFilesStorage')
            ->withAnyParameters();

        $controller = $this->getMockBuilder(FixtureController::class)
            ->setMethods(array('getFixtureHandler'))
            ->getMock();

        $controller->method('getFixtureHandler')->willReturn($fixtureHandler);

        // Execute expected code

        $response = $controller->truncateDatabaseAction();

        $this->assertEquals('done.', $response->getContent());
    }

    public function testGetFixtureHandler()
    {
        // Initialize SUT object $controller and configure mock expectations

        $fixtureHandler = $this->getMockBuilder(DefaultFixtureHandler::class)
            ->disableOriginalConstructor()
            ->getMock();

        $controller = $this->getMockBuilder(FixtureController::class)
            ->setMethods(array('get'))
            ->getMock();

        $controller->method('get')->willReturn($fixtureHandler);

        $controller->expects($this->once())
            ->method('get')
            ->with('app.default_fixture_handler');

        // Execute expected code

        $this->assertEquals($fixtureHandler, $controller->getFixtureHandler());
    }
}
