<?php

namespace AppBundle\Tests\FixtureImporter;

use AppBundle\Entity\Album;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\FixtureCleaner\DefaultFixtureCleaner;
use AppBundle\FixtureImporter\DefaultFixtureImporter;
use Doctrine\ORM\EntityManager;

class DefaultFixtureHandlerTest extends WebTestCase
{
    private $imageStoragePath = '';

    public function testCleanDatabase()
    {
        // Initialize SUT object $fixtureCleaner and configure mock expectations

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

        $fixtureCleaner = new DefaultFixtureCleaner($em, $this->imageStoragePath);

        // Execute expected code

        $fixtureCleaner->cleanDatabase();
    }
}