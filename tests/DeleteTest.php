<?php

class DeleteTest extends PHPUnit_Framework_TestCase
{
	public $query;
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Delete('user');
	}

	public function testAll()
	{
		$sql = "DELETE FROM user";
		$this->assertSame($sql, $this->query->compile());
	}

	public function testWhere()
	{
		$this->query->where('user_id', '=', 1);

		$sql = "DELETE FROM user WHERE user_id = ?";
		$this->assertSame($sql, $this->query->compile());
		$this->assertSame(array(1), $this->query->getParams());
	}

	public function testOrderBy()
	{
		$this->query->orderBy('create_date', 'ASC');

		$sql = "DELETE FROM user ORDER BY create_date ASC";
		$this->assertSame($sql, $this->query->compile());
	}

	public function testLimit()
	{
		$this->query->limit(2);

		$sql = "DELETE FROM user LIMIT 2";
		$this->assertSame($sql, $this->query->compile());
	}

}
