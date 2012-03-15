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
		$where->where("name", "=", "Dave");

		$this->assertEquals("WHERE name = 'Dave'", $where->compile());
	}
}