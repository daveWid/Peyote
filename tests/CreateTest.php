<?php

class CreateTest extends PHPUnit_Framework_TestCase
{
	public $create;

	public function setUp()
	{
		parent::setUp();
		$this->create = new \Peyote\Create('user');

		// Mixing columns and strings...
		$this->create->setColumns(array(
			new \Peyote\Column('user_id', 'serial'),
			new \Peyote\Column('name', 'varchar', array('length' => 50, 'is_null' => false)),
			'password varchar(100) NOT NULL',
			new \Peyote\Column('create_date', 'timestamp', array('is_null' => false, 'default' => 'CURRENT_TIMESTAMP'))
		));
	}

	public function testOutput()
	{
		$expected = 'CREATE TABLE user ( user_id INT UNSIGNED NOT NULL AUTO_INCREMENT, '.
			'name varchar(50) NOT NULL, ' .
			'password varchar(100) NOT NULL, ' .
			'create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, ' .
			'PRIMARY KEY (user_id) ' .
			') ENGINE=MyISAM DEFAULT CHARSET=utf8';

		$this->assertSame($expected, $this->create->compile());
	}

	public function testPrimaryKeyIsEmptyByDefault()
	{
		$this->assertEmpty($this->create->getPrimaryKey());
	}

	public function testDefaultCharset()
	{
		$this->assertSame("utf8", $this->create->getCharset());
	}

	public function testCharset()
	{
		$this->create->setCharset("ISO-8859-1");
		$this->assertSame("ISO-8859-1", $this->create->getCharset());
	}

	public function testDefaultEngine()
	{
		$this->assertSame("MyISAM", $this->create->getEngine());
	}

	public function testEngine()
	{
		$this->create->setEngine("InnoDB");
		$this->assertSame("InnoDB", $this->create->getEngine());
	}

	// Bad example, but it is the principal...
	public function testCompositePrimaryKeys()
	{
		$this->create->setPrimaryKey(array('user_id', 'create_date'));

		$expected = 'CREATE TABLE user ( user_id INT UNSIGNED NOT NULL AUTO_INCREMENT, '.
			'name varchar(50) NOT NULL, ' .
			'password varchar(100) NOT NULL, ' .
			'create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, ' .
			'PRIMARY KEY (user_id, create_date) ' .
			') ENGINE=MyISAM DEFAULT CHARSET=utf8';

		$this->assertSame($expected, $this->create->compile());
	}

	public function testSetPrimaryKeyWithString()
	{
		$this->create->setPrimaryKey('create_date');
		$this->assertSame(array('create_date'), $this->create->getPrimaryKey());
	}

	public function testIfExistsInCompile()
	{
		$this->create->setIfExists(true);

		$expected = 'CREATE TABLE IF NOT EXISTS user ( user_id INT UNSIGNED NOT NULL AUTO_INCREMENT, '.
			'name varchar(50) NOT NULL, ' .
			'password varchar(100) NOT NULL, ' .
			'create_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, ' .
			'PRIMARY KEY (user_id) ' .
			') ENGINE=MyISAM DEFAULT CHARSET=utf8';

		$this->assertSame($expected, $this->create->compile());
	}

}
