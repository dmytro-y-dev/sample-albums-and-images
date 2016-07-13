<?php

namespace AppBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumRepositoryTest extends WebTestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->em->close();
        $this->em = null; // avoid memory leaks
    }

    public function testFindAllWithoutQueryingImages()
    {
        $albums = $this->em
            ->getRepository('AppBundle:Album')
            ->findAllWithoutQueryingImages()
        ;

        $this->assertEquals(5, count($albums));
    }

    public function testFindOneWithoutQueryingImages()
    {
        $album = $this->em
            ->getRepository('AppBundle:Album')
            ->findOneWithoutQueryingImages(6)
        ;

        $this->assertEquals(6, $album['id']);
    }

    public function testFindAllWithMaxImages()
    {
        $albums = $this->em
            ->getRepository('AppBundle:Album')
            ->findAllWithMaxImages(10)
        ;

        $this->assertEquals(1, count($albums));
    }
}
