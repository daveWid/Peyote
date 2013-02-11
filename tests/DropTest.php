<?php

class DropTest extends PHPUnit_Framework_TestCase
{
	public $drop;

	public function setUp()
	{
		parent::setUp();
		$this->drop = new \Peyote\Drop('test');
	}

	public function testDrop()
	{
		$this->assertSame(
			'DROP TABLE test',
			$this->drop->compile()
		);
	}

	public function testDropIfExists()
	{
		$this->drop->setIfExists(true);
		$this->assertSame(
			'DROP TABLE test IF EXISTS',
			$this->drop->compile()
		);
	}
}
