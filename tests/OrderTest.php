<?php

class OrderTest extends PHPUnit_Framework_TestCase
{
	public $query;
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Order;
	}

	public function testEmpty()
	{
		$this->assertSame("", $this->query->compile());
	}

	public function testOrder()
	{
		$this->query->orderBy('create_date');
		$this->assertSame("ORDER BY create_date", $this->query->compile());
	}

	public function testOrderWithDirection()
	{
		$this->query->orderBy('create_date', 'DESC');
		$this->assertSame("ORDER BY create_date DESC", $this->query->compile());
	}

	public function testTwoOrders()
	{
		$this->query->orderBy('create_date', 'DESC')->orderBy('user_id', 'ASC');
		$this->assertSame("ORDER BY create_date DESC, user_id ASC", $this->query->compile());
	}

}
