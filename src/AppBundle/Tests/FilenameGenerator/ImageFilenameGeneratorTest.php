<?php

namespace AppBundle\Tests\FilenameGenerator;

use AppBundle\FilenameGenerator\ImageFilenameGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ImageFilenameGeneratorTest extends WebTestCase
{
    public function testGenerateGood()
    {
        $image = $this->createMock("AppBundle\\Entity\\Image");
        $image->method('getCreated')->willReturn(new \DateTime());

        $expectedName = $image->getCreated()->getTimestamp() . '_test.jpg';

        $this->assertEquals($expectedName, ImageFilenameGenerator::generate('test', '.jpg', $image));
    }

    public function testGenerateBad()
    {
        $this->expectException(\Exception::class);

        ImageFilenameGenerator::generate('test', '.jpg', null);
    }
}
