<?php

use \Peyote\Facade as Peyote;

class PeyoteFacadeTest extends PHPUnit_Framework_TestCase
{
	public function testSelect()
	{
		$this->assertInstanceOf("\Peyote\Select", Peyote::select('test'));
	}

	public function testInsert()
	{
		$this->assertInstanceOf("\Peyote\Insert", Peyote::insert('test'));
	}

	public function testUpdate()
	{
		$this->assertInstanceOf("\Peyote\Update", Peyote::update('test'));
	}

	public function testDelete()
	{
		$this->assertInstanceOf("\Peyote\Delete", Peyote::delete('test'));
	}

}
