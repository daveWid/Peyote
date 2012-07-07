<?php

class LimitTest extends PHPUnit_Framework_TestCase
{
	public $query;
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Limit;
	}

	public function testEmpty()
	{
		$this->assertSame("", $this->query->compile());
	}

	public function testLimit()
	{
		$this->query->setLimit(4);
		$this->assertSame("LIMIT 4", $this->query->compile());
	}
}
