<?php

namespace AppBundle\Tests\Repository;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageRepositoryTest extends WebTestCase
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

    public function testQueryAllByAlbum()
    {
        $images = $this->em
            ->getRepository('AppBundle:Image')
            ->queryAllByAlbum(6)
            ->getResult()
        ;

        $this->assertEquals(5, count($images));
    }
}
