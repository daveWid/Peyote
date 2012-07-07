<?php

class GroupTest extends PHPUnit_Framework_TestCase
{
	public $query;
	
	public function setUp()
	{
		parent::setUp();
		$this->query = new \Peyote\Group;
	}

	public function testEmpty()
	{
		$this->assertSame("", $this->query->compile());
	}

	public function testGroup()
	{
		$this->query->groupBy('create_date');
		$this->assertSame("GROUP BY create_date", $this->query->compile());
	}

	public function testGroupWithDirection()
	{
		$this->query->groupBy('create_date', 'DESC');
		$this->assertSame("GROUP BY create_date DESC", $this->query->compile());
	}

	public function testTwoGroups()
	{
		$this->query->groupBy('create_date', 'DESC')->groupBy('user_id', 'ASC');
		$this->assertSame("GROUP BY create_date DESC, user_id ASC", $this->query->compile());
	}

}
