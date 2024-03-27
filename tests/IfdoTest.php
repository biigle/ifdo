<?php
namespace Biigle\IfdoParser\Tests;

use Biigle\IfdoParser\Ifdo;
use PHPUnit\Framework\TestCase;

class IfdoTest extends TestCase
{
	public function testReadFromFile()
	{
		$obj = Ifdo::fromFile(__DIR__ . '/fixtures/ifdo-test-v2.0.0.json');
		$obj->setDebug(true);
		$this->assertEquals(true, $obj->isValid());
	}
}
