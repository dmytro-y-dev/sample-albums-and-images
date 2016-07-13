<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('<div id="overall">', $crawler->filter('body')->html());
        $this->assertContains('<section id="albums-content">', $crawler->filter('body')->html());
        $this->assertContains('<section id="images-content">', $crawler->filter('body')->html());
    }
}
