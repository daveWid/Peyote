<?php

/**
 * A class for testing Trait access into classes.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class TraitTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Testing get_trait()
	 */
	public function testGet()
	{
		$select = new \Peyote\Select;
		$select->table("testing")->limit(5);

		$this->assertInstanceOf("\\Peyote\\Limit", $select->get_trait("limit"));
	}

	/**
	 * Testing set_trait()
	 */
	public function testSet()
	{
		$limit = new \Peyote\Limit;
		$limit->set_limit(5);

		$select = new \Peyote\Select;
		$select->table("testing");
		$select->set_trait("limit", $limit);

		$this->assertEquals("SELECT * FROM testing LIMIT 5", $select->compile());
	}

	/**
	 * Testing get_$trait()
	 */
	public function testMagicGet()
	{
		$select = new \Peyote\Select;
		$select->table("testing")->limit(5);

		$this->assertInstanceOf("\\Peyote\\Limit", $select->get_limit());
	}

	/**
	 * Testing set_$trait()
	 */
	public function testMagicSet()
	{
		$limit = new \Peyote\Limit;
		$limit->set_limit(5);

		$select = new \Peyote\Select;
		$select->table("testing");
		$select->set_limit($limit);

		$this->assertEquals("SELECT * FROM testing LIMIT 5", $select->compile());
	}

	/**
	 * @expectedException  \Peyote\Exception
	 */
	public function testGetException()
	{
		$select = new \Peyote\Select;
		$select->get_trait("throwerror");
	}

	/**
	 * @expectedException  \Peyote\Exception
	 */
	public function testSetException()
	{
		$limit = new \Peyote\Limit;
		$limit->set_limit(5);

		$select = new \Peyote\Select;
		$select->set_where($limit);
	}

}