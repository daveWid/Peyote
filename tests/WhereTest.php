<?php

class WhereTest extends PHPUnit_Framework_TestCase
{
	public $query;
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Where;
	}

	public function testEmpty()
	{
		$this->assertSame("", $this->query->compile());
	}

	public function testAnd()
	{
		$this->query->andWhere('user_id', '=', 1);

		$this->assertSame("WHERE user_id = ?", $this->query->compile());
		$this->assertSame(array(1), $this->query->getParams());
	}

	public function testOr()
	{
		$this->query->orWhere('user_id', '=', 1);

		$this->assertSame("WHERE user_id = ?", $this->query->compile());
		$this->assertSame(array(1), $this->query->getParams());
	}

	public function testAndThenOr()
	{
		$this->query->andWhere('user_id', '=', 1)->orWhere('user_id', '=', 2);

		$this->assertSame("WHERE user_id = ? OR user_id = ?", $this->query->compile());
		$this->assertSame(array(1, 2), $this->query->getParams());
	}

	public function testOrThenAnd()
	{
		$this->query->orWhere('user_id', '=', 1)->andWhere('user_id', '=', 2);

		$this->assertSame("WHERE user_id = ? AND user_id = ?", $this->query->compile());
		$this->assertSame(array(1, 2), $this->query->getParams());
	}

}
