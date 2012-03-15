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
	 * Testing USING statement
	 */
	public function testUsing()
	{
		$join = new \Peyote\Join;
		$join->join("role")->using('id');

		$this->assertEquals("JOIN role USING(id)", $join->compile());
	}

	/**
	 * Testing JOIN modifiers
	 */
	public function testModifier()
	{
		$join = new \Peyote\Join;
		$join->join("role", "left")->on("user.id", "=", "role.id");

		$this->assertEquals("LEFT JOIN role ON user.id = role.id", $join->compile());
	}

	/**
	 * Testing a subquery
	 */
	public function testSubquery()
	{
		$query = "INNER JOIN ( SELECT authid, COUNT(bookid) FROM authorbook AS a2 GROUP BY authid HAVING COUNT(bookid) > 1 ) ON a1.authid = a3.authid";

		$subquery = new \Peyote\Select;
		$subquery->columns("authid","COUNT(bookid)")
			->table(array("authorbook", "a2"))
			->group_by("authid")
			->having("COUNT(bookid)", ">", 1);

		$join = new \Peyote\Join;
		$join->join($subquery, "inner")->on("a1.authid", "=", "a3.authid");

		$this->assertEquals($query, $join->compile());
	}

	/**
	 * Testing a subquery with an alias
	 */
	public function testSubqueryAlias()
	{
		$query = "OUTER JOIN ( SELECT authid, COUNT(bookid) FROM authorbook AS a2 GROUP BY authid HAVING COUNT(bookid) > 1 ) AS a3 ON a1.authid = a3.authid";

		$subquery = new \Peyote\Select;
		$subquery->columns("authid","COUNT(bookid)")
			->table(array("authorbook", "a2"))
			->group_by("authid")
			->having("COUNT(bookid)", ">", 1);

		$join = new \Peyote\Join;
		$join->join(array($subquery, "a3"), "outer")->on("a1.authid", "=", "a3.authid");

		$this->assertEquals($query, $join->compile());
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