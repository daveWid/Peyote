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
	 * Make sure that where clauses are being picked up correctly.
	 * Full testing of the where clauses is in its own class.
	 */
	public function testWhere()
	{
		$delete = new \Peyote\Delete("testing");
		$delete->where("name", "=", "Dave");

		$this->assertEquals("DELETE FROM testing WHERE name = Dave", $delete->compile());
	}

	/**
	 * Make sure that order_by is being picked up correctly.
	 */
	public function testOrderBy()
	{
		$delete = new \Peyote\Delete("testing");
		$delete->order_by("name", "DESC");

		$this->assertEquals("DELETE FROM testing ORDER BY name DESC", $delete->compile());
	}

	/**
	 * Make sure that limit is being picked up correctly.
	 */
	public function testLimit()
	{
		$delete = new \Peyote\Delete("testing");
		$delete->limit(5);

		$this->assertEquals("DELETE FROM testing LIMIT 5", $delete->compile());
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

}