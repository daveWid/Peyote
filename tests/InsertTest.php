<?php

/**
 * A class for testing the Insert class.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class InsertTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var array  An array of names we can use for testing.
	 */
	protected $names = array(
		array('name' => "Larry"),
		array('name' => "Curly"),
		array('name' => "Moe")
	);

	/**
	 * Test to make sure that a single row is built correctly
	 */
	public function testSingleRow()
	{
		$insert = new \Peyote\Insert;
		$insert->table("testing")
			->columns(array_keys($this->names[0]))
			->values(array_values($this->names[0]));

		$this->assertEquals("INSERT INTO testing (name) VALUES (Larry)", $insert->compile());
	}

	/**
	 * A test for multiple rows. 
	 */
	public function testMultipleRows()
	{
		$names = array();
		foreach ($this->names as $n)
		{
			$names[] = array($n['name']);
		}

		$insert = new \Peyote\Insert;
		$insert->table("testing")
			->columns(array_keys($this->names[0]))
			->values($names);

		$this->assertEquals("INSERT INTO testing (name) VALUES (Larry), (Curly), (Moe)", $insert->compile());
	}

	/**
	 * A test for the ignore flag 
	 */
	public function testIgnore()
	{
		$insert = new \Peyote\Insert;
		$insert->table("testing")
			->ignore()
			->columns(array_keys($this->names[0]))
			->values(array_values($this->names[0]));

		$this->assertEquals("INSERT IGNORE INTO testing (name) VALUES (Larry)", $insert->compile());
	}

	// TODO: Test the errors that are thrown by mixing and matching values() and select()
	//       To do this the Select class needs to be built first

	// TODO: Test the on_duplicate_key function, but the Update class needs to be finished first
}