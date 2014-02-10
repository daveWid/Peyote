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
		$this->assertSame("LIMIT 0, 4", $this->query->compile());
	}
	
	public function testOffset()
	{
		$this->query->setLimit(4, 2);
		$this->assertSame("LIMIT 2, 4", $this->query->compile());
	}
}
