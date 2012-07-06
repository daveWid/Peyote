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
	 * Testing getTrait()
	 */
	public function testGet()
	{
		$select = new \Peyote\Select;
		$select->table("testing")->limit(5);

		$this->assertInstanceOf("\\Peyote\\Limit", $select->getTrait("limit"));
	}

	/**
	 * Testing setTrait()
	 */
	public function testSet()
	{
		$limit = new \Peyote\Limit;
		$limit->setLimit(5);

		$select = new \Peyote\Select;
		$select->table("testing");
		$select->setTrait("limit", $limit);

		$this->assertEquals("SELECT * FROM testing LIMIT 5", $select->compile());
	}

	/**
	 * Testing get$trait()
	 */
	public function testMagicGet()
	{
		$select = new \Peyote\Select;
		$select->table("testing")->limit(5);

		$this->assertInstanceOf("\\Peyote\\Limit", $select->getLimit());
	}

	/**
	 * Testing set$trait()
	 */
	public function testMagicSet()
	{
		$limit = new \Peyote\Limit;
		$limit->setLimit(5);

		$select = new \Peyote\Select;
		$select->table("testing");
		$select->setLimit($limit);

		$this->assertEquals("SELECT * FROM testing LIMIT 5", $select->compile());
	}

	/**
	 * @expectedException  \Peyote\Exception
	 */
	public function testGetException()
	{
		$select = new \Peyote\Select;
		$select->getTrait("throwerror");
	}

	/**
	 * @expectedException  \Peyote\Exception
	 */
	public function testSetException()
	{
		$limit = new \Peyote\Limit;
		$limit->setLimit(5);

		$select = new \Peyote\Select;
		$select->setWhere($limit);
	}

}