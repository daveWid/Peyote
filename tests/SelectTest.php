<?php

class SelectTest extends PHPUnit_Framework_TestCase
{
	public $query;
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Select('user');
	}

	public function testAll()
	{
		$this->assertSame("SELECT * FROM user", $this->query->compile());
	}

	public function testDistinct()
	{
		$this->query->distinct();
		$this->assertSame("SELECT DISTINCT * FROM user", $this->query->compile());
	}

	public function testColumns()
	{
		$this->query->columns('name','user_id');
		$this->assertSame("SELECT name, user_id FROM user", $this->query->compile());
	}

	public function testJoin()
	{
		$this->query->join('role')->using('user_id');
		$sql = "SELECT * FROM user JOIN role USING(user_id)";
		$this->assertSame($sql, $this->query->compile());
	}

	public function testWhere()
	{
		$this->query->where('user_id', '=', 1);
		$sql = 'SELECT * FROM user WHERE user_id = ?';
		$this->assertSame($sql, $this->query->compile());
		$this->assertSame(array(1), $this->query->getParams());
	}
	
	public function testHaving()
	{
		$this->query->having('id', '>', 1);
		$sql = 'SELECT * FROM user HAVING id > ?';
		$this->assertSame($sql, $this->query->compile());
		$this->assertSame(array(1), $this->query->getParams());
	}
	
	public function testWhereHaving()
	{
		$this->query->where('user_id', '=', 5);
		$this->query->having('id', '>', 1);
		$sql = 'SELECT * FROM user WHERE user_id = ? HAVING id > ?';
		$this->assertSame($sql, $this->query->compile());
		$this->assertSame(array(5, 1), $this->query->getParams());
	}

	public function testGroupBy()
	{
		$this->query->groupBy('user_id', 'DESC');
		$sql = 'SELECT * FROM user GROUP BY user_id DESC';
		$this->assertSame($sql, $this->query->compile());
	}

	public function testOrderBy()
	{
		$this->query->orderBy('user_id', 'ASC');
		$sql = 'SELECT * FROM user ORDER BY user_id ASC';
		$this->assertSame($sql, $this->query->compile());
	}

	public function testLimit()
	{
		$this->query->limit(1);
		$sql = 'SELECT * FROM user LIMIT 1';
		$this->assertSame($sql, $this->query->compile());
	}

	public function testOffset()
	{
		$this->query->offset(10);
		$sql = 'SELECT * FROM user OFFSET 10';
		$this->assertSame($sql, $this->query->compile());
	}

	/**
	 * @expectedException \Peyote\Exception
	 */
	public function testUndefinedMethod()
	{
		$this->query->undefinedMethod();
	}

}
