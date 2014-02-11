<?php

use Peyote\Model;

class ModelTest extends PHPUnit_Framework_TestCase
{
	public $model;

	public function setUp()
	{
		parent::setUp();

		$this->model = new Model(array(
			'name' => "Dave",
			'author' => true
		));
	}

	public function testHas()
	{
		$this->assertTrue($this->model->has('name'));
	}

	public function testHasNot()
	{
		$this->assertFalse($this->model->has('nope'));
	}

	public function testGet()
	{
		$this->assertSame('Dave', $this->model->get('name'));
	}

	public function testGetNullIfDoesntExist()
	{
		$this->assertNull($this->model->get('nope'));
	}

	public function testGetCustomDefault()
	{
		$this->assertFalse($this->model->get('nope', false));
	}

	public function testSet()
	{
		$this->model->set('extra', "Yes!");
		$this->assertSame('Yes!', $this->model->get('extra'));
	}

	public function testSetArray()
	{
		$data = array('name' => 'David', 'author' => false);
		$this->model->set($data);

		$this->assertSame($data, $this->model->toArray());
	}

	public function testDelete()
	{
		$this->model->delete('author');
		$this->assertNull($this->model->get('author'));
	}

	public function testValidateIsEmptyArrayByDefault()
	{
		$this->assertEmpty($this->model->validate());
	}

	public function testClear()
	{
		$this->model->clear();
		$this->assertEmpty($this->model->toArray());
	}

	public function testToArray()
	{
		$this->assertSame(array(
			'name' => 'Dave',
			'author' => true
		), $this->model->toArray());
	}

	public function testToJson()
	{
		$this->assertSame('{"name":"Dave","author":true}', $this->model->toJSON());
		$this->assertSame('{"name":"Dave","author":true}', $this->model->jsonSerialize());
	}

	public function testObjectAccess()
	{
		$this->assertSame('Dave', $this->model->name);
	}

	public function testObjectSet()
	{
		$this->model->extra = 'Yes!';
		$this->assertSame('Yes!', $this->model->extra);
	}

	public function testArrayAccessGet()
	{
		$this->assertSame('Dave', $this->model['name']);
	}

	public function testArrayAccessSet()
	{
		$this->model['extra'] = "Yes!";
		$this->assertSame("Yes!", $this->model['extra']);
	}

	public function testObjectIsset()
	{
		$this->assertFalse(isset($this->model->nope));
	}

	public function testIteratorAggregate()
	{
		$this->assertInstanceOf('ArrayIterator', $this->model->getIterator());
	}

	public function testIsNew()
	{
		$this->assertTrue($this->model->isNew());
	}

	public function testIsNotNew()
	{
		$this->model->set(array(
			'id' => 1,
			'username' => 'dwidmer'
		));

		$this->assertFalse($this->model->isNew());
	}

	public function testGetModifiedData()
	{
		$data = array(
			'username' => 'dwidmer',
			'password' => 'youllneverguess'
		);

		$this->model->set($data);
		$this->assertSame($data, $this->model->getModifiedData());
	}

	public function testResetClearsModifiedData()
	{
		$data = array(
			'username' => 'dwidmer',
			'password' => 'youllneverguess'
		);

		$this->model->set($data);
		// save here IRL...
		$this->model->reset();

		$this->assertSame(array(), $this->model->getModifiedData());
	}

}
