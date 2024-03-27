<?php

namespace Biigle\IfdoParser\Tests;

use Biigle\IfdoParser\IfdoParser;
use PHPUnit\Framework\TestCase;

class IfdoParserTest extends TestCase
{
    public function testSayHello()
    {
        $helloWorld = new IfdoParser();
        $this->assertEquals('Hello World!', $helloWorld->sayHello());
    }
}
