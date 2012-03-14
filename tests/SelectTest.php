<?php

/**
 * A class for testing the Select class.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class SelectTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Testing a very simple select statement
	 */
	public function testSimple()
	{
		$select = new \Peyote\Select;
		$select->table("testing");

		$this->assertEquals("SELECT * FROM testing", $select->compile());
	}

	/**
	 * Testing a very simple select statement
	 */
	public function testColumns()
	{
		$select = new \Peyote\Select;
		$select->table("heroes")->columns("name","alias");

		$this->assertEquals("SELECT name, alias FROM heroes", $select->compile());
	}

	/**
	 * Testing a very simple select statement
	 */
	public function testColumnsArray()
	{
		$columns = array('name', 'alias');

		$select = new \Peyote\Select;
		$select->table("heroes")->columns_array($columns);

		$this->assertEquals("SELECT name, alias FROM heroes", $select->compile());
	}

	/**
	 * Test a built-in function 
	 */
	public function testFunctions()
	{
		$select = new \Peyote\Select;
		$select->table('heroes')
			->columns(array("SUM(evil)", 'evil'));

		$this->assertEquals("SELECT SUM(evil) AS evil FROM heroes", $select->compile());
	}

}