<?php

class JoinTest extends PHPUnit_Framework_TestCase
{
	public $query;
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Join;
	}

	public function testEmpty()
	{
		$this->assertSame("", $this->query->compile());
	}

	public function testOn()
	{
		$this->query->addJoin('role')->on('user.user_id', '=', 'role.user_id');
		$this->assertSame("JOIN role ON user.user_id = role.user_id", $this->query->compile());
	}

	/**
     * @expectedException \Peyote\Exception
     */
	public function testOnWithoutJoin()
	{
		$this->query->on('user.user_id', '=', 'role.user_id');
	}

	public function testUsing()
	{
		$this->query->addJoin('role')->using('user_id');
		$this->assertSame("JOIN role USING(user_id)", $this->query->compile());
	}

	/**
     * @expectedException \Peyote\Exception
     */
	public function testUsingWithoutJoin()
	{
		$this->query->using('user.user_id', '=', 'role.user_id');
	}

	public function testOnAndUsing()
	{
		$this->query
			->addJoin('role')->on('user.user_id', '=', 'role.user_id')
			->addJoin('role')->using('user_id');

		$sql = "JOIN role ON user.user_id = role.user_id, JOIN role USING(user_id)";
		$this->assertSame($sql, $this->query->compile());
	}

	public function testOnWithType()
	{
		$this->query->addJoin('role', "LEFT")->on('user.user_id', '=', 'role.user_id');
		$this->assertSame("LEFT JOIN role ON user.user_id = role.user_id", $this->query->compile());
	}

	public function testUsingWithType()
	{
		$this->query->addJoin('role', "NATURAL")->using('user_id');
		$this->assertSame("NATURAL JOIN role USING(user_id)", $this->query->compile());
	}

}
