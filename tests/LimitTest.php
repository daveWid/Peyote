<?php

/**
 * A class for testing the Limit class.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class LimitTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Quick test for limit 
	 */
	public function testLimit()
	{
		$limit = new \Peyote\Limit;
		$limit->set_limit(5);

		$this->assertEquals("LIMIT 5", $limit->compile());
	}

	/**
	 * Quick test for offset
	 */
	public function testOffest()
	{
		$limit = new \Peyote\Limit;
		$limit->set_limit(5)->offset(10);

		$this->assertEquals("LIMIT 5 OFFSET 10", $limit->compile());
	}

	/**
	 * Make sure that the using both params of limit and using limit then offset
	 * are equal. 
	 */
	public function testLimitOffset()
	{
		$limit = new \Peyote\Limit;
		$limit->set_limit(5, 10);

		$offset = new \Peyote\Limit;
		$offset->set_limit(5)->offset(10);

		$this->assertEquals($limit->compile(), $offset->compile());
	}

	/**
	 * Test to make sure that if you only supply offset that the output is an 
	 * empty string
	 */
	public function testOffsetOnlyEmpty()
	{
		$limit = new \Peyote\Limit;
		$limit->offset(10);

		$this->assertEquals("", $limit->compile());
	}

}