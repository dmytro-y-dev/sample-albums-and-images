<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageControllerTest extends WebTestCase
{
    public function testGetPaginatedImagesNoPagination()
    {
        $client = static::createClient();

        $client->request('GET', '/api/albums/6/page/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseArray = @json_decode($client->getResponse()->getContent(), true);

        $pagination = trim($responseArray['pagination']);
        $images = $responseArray['images'];

        $this->assertNotEmpty($images);
        $this->assertEmpty($pagination);
    }

    public function testGetPaginatedImagesWithPagination()
    {
        $client = static::createClient();

        $client->request('GET', '/api/albums/7/page/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseArray = @json_decode($client->getResponse()->getContent(), true);

        $pagination = $responseArray['pagination'];
        $images = $responseArray['images'];

        $this->assertNotEmpty($images);
        $this->assertNotEmpty($pagination);
    }
}
