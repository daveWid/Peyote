<?php

/**
 * A class for testing the Delete class.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class DeleteTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Testing a simple delete all
	 */
	public function testAll()
	{
		$delete = new \Peyote\Delete;
		$delete->table("testing");

		$this->assertEquals("DELETE FROM testing", $delete->compile());
	}

	/**
	 * Testing a table alias 
	 */
	public function testAlias()
	{
		$delete = new \Peyote\Delete;
		$delete->table(array('testing', 't'));

		$this->assertEquals("DELETE FROM testing AS t", $delete->compile());
	}

	/**
	 * Tests for an error when a missing method is called
	 *
	 * @expectedException \Peyote\Exception
	 */
	public function testMissingMethod()
	{
		$delete = new \Peyote\Delete("testing");
		$delete->doesnotexist();
	}

	/**
	 * Makes sure that a delete object is still \Peyote\Delete after a lot of chaining.
	 */
	public function testChaining()
	{
		$delete = new \Peyote\Delete;
		$delete->table("testing")->where("name", '=', 'Dave')->orderBy("name")->limit(1);

		$this->assertInstanceOf("\\Peyote\\Delete", $delete);
	}

}