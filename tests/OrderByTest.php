<?php

/**
 * Tests the OrderBy functionality.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class OrderByTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Make sure that order_by is being picked up correctly.
	 */
	public function testNoDirection()
	{
		$order = new \Peyote\OrderBy;
		$order->orderBy("name");

		$this->assertEquals("ORDER BY name", $order->compile());
	}

	/**
	 * Test the direction
	 */
	public function testDirection()
	{
		$order = new \Peyote\OrderBy;
		$order->orderBy("name", "DESC");

		$this->assertEquals("ORDER BY name DESC", $order->compile());
	}
}