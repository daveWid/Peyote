<?php

/**
 * A class for testing WHERE clauses.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class WhereTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Just making sure a simple where is building correctly.
	 */
	public function testWhere()
	{
		$where = new \Peyote\Where;
		$where->and_where("name", "=", "Dave");

		$this->assertEquals("WHERE name = 'Dave'", $where->compile());
	}

	/**
	 * Tests a BETWEEN clause
	 */
	public function testBetween()
	{
		$where = new \Peyote\Where;
		$where->and_where("date", "BETWEEN", array("2012-03-15", "2012-04-02"));

		$this->assertEquals("WHERE date BETWEEN '2012-03-15' AND '2012-04-02'", $where->compile());
	}
}