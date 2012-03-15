<?php

/**
 * A class for testing Joins class.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class JoinTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Testing an ON statement 
	 */
	public function testOn()
	{
		$join = new \Peyote\Join;
		$join->join("role")->on("user.id", "=", "role.id");

		$this->assertEquals("JOIN role ON user.id = role.id", $join->compile());
	}

	/**
	 * Testing USING statement. 
	 */
	public function testUsing()
	{
		$join = new \Peyote\Join;
		$join->join("role")->using('id');

		$this->assertEquals("JOIN role USING(id)", $join->compile());
	}

	/**
	 * @expectedException \Peyote\Exception
	 */
	public function testOnException()
	{
		$join = new \Peyote\Join;
		$join->on("user.id", "=", "role.id");
	}

	/**
	 * @expectedException \Peyote\Exception
	 */
	public function testUsingException()
	{
		$join = new \Peyote\Join;
		$join->using('id');
	}

}