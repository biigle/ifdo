<?php
namespace Biigle\IfdoParser\Tests;

use Biigle\IfdoParser\Ifdo;
use PHPUnit\Framework\TestCase;

class IfdoTest extends TestCase
{
    public function testReadFromFile()
    {
        $path = __DIR__ . '/fixtures/ifdo-test-v2.0.0.json';
        $obj  = Ifdo::fromFile($path);
        $obj->setDebug(true);
        $this->assertEquals(true, $obj->isValid());

        $this->assertEquals(json_encode(json_decode(file_get_contents($path))), $obj->toString());
    }

    public function testImageExample()
    {
        $obj = Ifdo::fromFile(__DIR__ . '/fixtures/image-example-1.json');
        $obj->setDebug(true);
        $this->assertEquals(true, $obj->isValid());
    }

    public function testVideoExample()
    {
        $obj = Ifdo::fromFile(__DIR__ . '/fixtures/video-example-1.json');
        $obj->setDebug(true);
        $this->assertEquals(true, $obj->isValid());
    }

    public function testStrictMode()
    {
        $this->expectException(\Exception::class);
        Ifdo::fromString('{"some": "json"}', true);
    }
}
