<?php

class HavingTest extends PHPUnit_Framework_TestCase
{
	public $query;
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Having;
	}

	public function testEmpty()
	{
		$this->assertSame("", $this->query->compile());
	}

	public function testAnd()
	{
		$this->query->andHaving('MAX(salary)', '>', 1000000);

		$this->assertSame("HAVING MAX(salary) > ?", $this->query->compile());
		$this->assertSame(array(1000000), $this->query->getParams());
	}

	public function testOr()
	{
		$this->query->orHaving('MAX(salary)', '>', 1000000);

		$this->assertSame("HAVING MAX(salary) > ?", $this->query->compile());
		$this->assertSame(array(1000000), $this->query->getParams());
	}

	public function testAndThenOr()
	{
		$this->query->andHaving('MAX(salary)', '>', 1000000)->orHaving('MIN(salary)', '<', 100);

		$this->assertSame("HAVING MAX(salary) > ? OR MIN(salary) < ?", $this->query->compile());
		$this->assertSame(array(1000000, 100), $this->query->getParams());
	}

	public function testOrThenAnd()
	{
		$this->query->orHaving('MAX(salary)', '>', 1000000)->andHaving('MIN(salary)', '<', 100);

		$this->assertSame("HAVING MAX(salary) > ? AND MIN(salary) < ?", $this->query->compile());
		$this->assertSame(array(1000000, 100), $this->query->getParams());
	}

}
