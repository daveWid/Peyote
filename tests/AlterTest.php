<?php

class AlterTest extends PHPUnit_Framework_TestCase
{
	public $alter;

	public function setUp()
	{
		parent::setUp();
		$this->alter = new \Peyote\Alter('user');
	}

	/**
	 * @expectedException \Peyote\Exception
	 */
	public function testNoConditionsThrowsException()
	{
		$this->alter->compile();
	}

	public function testAddColumn()
	{
		$this->alter->addColumn('activated', 'TINYINT NOT NULL');

		$expected = 'ALTER TABLE user ADD activated TINYINT NOT NULL';
		$this->doAssert($expected);
	}

	public function testAddPrimaryKey()
	{
		$this->alter->addPrimaryKey('user_id');
		$this->doAssert("ALTER TABLE user ADD PRIMARY KEY (user_id)");
	}

	public function testAddIndex()
	{
		$this->alter->addIndex('user_id');
		$this->doAssert("ALTER TABLE user ADD INDEX (user_id)");
	}

	public function testAddIndexUnique()
	{
		$this->alter->addIndex('user_id', "UNIQUE INDEX");
		$this->doAssert("ALTER TABLE user ADD UNIQUE INDEX (user_id)");
	}

	public function testDropColumn()
	{
		$this->alter->dropColumn('activated');

		$expected = "ALTER TABLE user DROP activated";
		$this->doAssert($expected);
	}

	public function testDropPrimaryKey()
	{
		$this->alter->dropPrimaryKey();
		$this->doAssert("ALTER TABLE user DROP PRIMARY KEY");
	}

	public function testDropIndex()
	{
		$this->alter->dropIndex('activated');
		$this->doAssert("ALTER TABLE user DROP INDEX activated");
	}

	private function doAssert($expected)
	{
		$this->assertSame($expected, $this->alter->compile());
	}

}
