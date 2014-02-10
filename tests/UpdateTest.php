<?php

class UpdateTest extends PHPUnit_Framework_TestCase
{
	public $query;

	public $data = array(
		'name' => "Testy McTesterson"
	);
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Update('user');
		$this->query->set($this->data);
	}

	public function testAll()
	{
		$sql = "UPDATE user SET name = ?";
		$this->assertSame($sql, $this->query->compile());
		$this->assertSame(array_values($this->data), $this->query->getParams());
	}

	public function testWhere()
	{
		$this->query->where('user_id', '=', 1);

		$sql = "UPDATE user SET name = ? WHERE user_id = ?";
		$this->assertSame($sql, $this->query->compile());
		$this->assertSame(array('Testy McTesterson', 1), $this->query->getParams());
	}

	public function testOrderBy()
	{
		$this->query->orderBy('create_date', 'ASC');

		$sql = "UPDATE user SET name = ? ORDER BY create_date ASC";
		$this->assertSame($sql, $this->query->compile());
	}

	public function testLimit()
	{
		$this->query->limit(2);

		$sql = "UPDATE user SET name = ? LIMIT 0, 2";
		$this->assertSame($sql, $this->query->compile());
	}

}
