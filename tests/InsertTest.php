<?php

class InsertTest extends PHPUnit_Framework_TestCase
{
	public $query;
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Insert('user');
	}

	public function testSimple()
	{
		$data = array(
			'email' => "dave@davewidmer.net",
			'password' => "youllneverguess"
		);

		$this->query->columns(array_keys($data))->values(array_values($data));

		$sql = "INSERT INTO user (email, password) VALUES (?, ?)";
		$this->assertSame($sql, $this->query->compile());
		$this->assertSame(array_values($data), $this->query->getParams());
	}

	public function testMultiple()
	{
		$data = array(
			'email' => "dave@davewidmer.net",
			'password' => "youllneverguess"
		);

		$values = array_values($data);
		$params = array_merge($values, $values);

		$this->query->columns(array_keys($data))->values($values)->values($values);

		$sql = "INSERT INTO user (email, password) VALUES (?, ?), (?, ?)";
		$this->assertSame($sql, $this->query->compile());
		$this->assertSame($params, $this->query->getParams());
	}

}
