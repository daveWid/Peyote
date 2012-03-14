<?php

/**
 * A class for testing the Expression class.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class ExpressionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Make sure what you put in is what you get back.
	 */
	public function testExpression()
	{
		$ex = new \Peyote\Expression("SUM(price)");
		$this->assertEquals("SUM(price)", $ex->compile());
	}
}