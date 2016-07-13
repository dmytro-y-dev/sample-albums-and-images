<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlbumControllerTest extends WebTestCase
{
    public function testGetAlbumBadId()
    {
        $client = static::createClient();

        $client->request('GET', '/api/albums/1');

        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    public function testGetAlbumGoodId()
    {
        $client = static::createClient();

        $client->request('GET', '/api/albums/6');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $album = @json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($album);
        $this->assertEquals(6, $album['id']);
        $this->assertNotEmpty($album['images']);
    }

    public function testGetAlbums()
    {
        $client = static::createClient();

        $client->request('GET', '/api/albums');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $albums = @json_decode($client->getResponse()->getContent(), true);

        $this->assertNotEmpty($albums);
        $this->assertEquals(5, count($albums));
    }

    public function testGetAlbumsWithMaxImages()
    {
        $client = static::createClient();

        $client->request('GET', '/api/albums/filter-max-images/10');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $albums = @json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals(1, count($albums));
    }
}
