<?php

use \Peyote\Model;

class CollectionTest extends PHPUnit_Framework_TestCase
{
	public $collection;
	public $m1;
	public $m2;

	public function setUp()
	{
		$this->collection = new \Peyote\Collection;
		$this->m1 = new Model(array('name' => "Dave"));
		$this->m2 = array('name' => "Nicholas");
	}

	public function testEmptyOnInit()
	{
		$this->assertSame(0, $this->collection->count());
	}

	public function testAddOneRetrieve()
	{
		$this->collection->addOne($this->m1);

		$this->assertSame(1, $this->collection->count());
		$this->assertSame($this->m1, $this->collection->at(0));
	}

	public function testAdd()
	{
		$this->collection->add(array($this->m1, $this->m2));

		$this->assertSame(2, $this->collection->count());
		$this->assertSame($this->m2, $this->collection->at(1));
	}

	public function testReplace()
	{
		$this->collection->add(array(0, 1, 2));
		$this->collection->replace(array(3, 4));

		$this->assertSame(2, $this->collection->count());
	}

	public function testReset()
	{
		$this->collection->add(array(0, 1, 2));
		$this->collection->reset();

		$this->assertSame(0, $this->collection->count());
	}

	public function testToArray()
	{
		$data = array(0, 1, 2);

		$this->collection->add($data);
		$this->assertSame($data, $this->collection->toArray());
	}

	public function testToArrayWithHashes()
	{
		$data = array(
			'name' => "Dave",
			'job' => "Web Developer"
		);

		$this->collection->add(array(
			new \Peyote\Model($data)
		));

		$this->assertSame(array($data), $this->collection->toArray());
	}

	public function testToJSON()
	{
		$this->collection->add(array(4, 5, 6));
		$this->assertSame("[4,5,6]", $this->collection->toJSON());
		$this->assertSame("[4,5,6]", $this->collection->jsonSerialize());
	}

	public function testToJSONWithHash()
	{
		$data = array(
			'name' => "Dave",
			'job' => "Web Developer"
		);

		$this->collection->add(array(
			new \Peyote\Model($data)
		));

		$this->assertSame('[{"name":"Dave","job":"Web Developer"}]', $this->collection->toJSON());
	}

	public function testArrayAccess()
	{
		$this->collection[0] = 'Foo';
		$this->assertSame('Foo', $this->collection[0]);

		unset($this->collection[0]);
		$this->assertFalse(isset($this->collection[0]));
	}

	public function testItererator()
	{
		$this->assertInstanceOf('ArrayIterator', $this->collection->getIterator());
	}

}
