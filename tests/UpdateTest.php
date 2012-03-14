<?php

/**
 * A class for testing the Update class.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class UpdateTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var array  Data we can use for update testing
	 */
	protected $hero = array(
		'name' => "Frank Castle",
		'alias' => "Punisher"
	);

	/**
	 * Testing a very simple update statement
	 */
	public function testSimple()
	{
		$update = new \Peyote\Update;
		$update->table("heroes")->set($this->hero);

		$this->assertEquals("UPDATE heroes SET name = 'Frank Castle', alias = 'Punisher'", $update->compile());
	}

}