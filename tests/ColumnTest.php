<?php

class ColumnTest extends PHPUnit_Framework_TestCase
{
	public $column;

	public function setUp()
	{
		parent::setUp();
		$this->column = new \Peyote\Column('user_id', 'INT');
	}

	public function testType()
	{
		$this->assertSame('INT', $this->column->getType());
	}

	public function testCompile()
	{
		$this->runAssert('user_id INT');
	}

	public function testIsNull()
	{
		$this->column->setIsNull(true);

		$this->assertTrue($this->column->getIsNull());
		$this->runAssert('user_id INT NULL');
	}

	public function testLength()
	{
		$this->column->setLength(5);

		$this->assertSame(5, $this->column->getLength());
		$this->runAssert('user_id INT(5)');
	}

	public function testOptions()
	{
		$this->column->addOption('UNSIGNED');

		$this->assertSame(array('UNSIGNED'), $this->column->getOptions());
		$this->runAssert('user_id INT UNSIGNED');
	}

	public function testAutoIncrementIsFalseByDefault()
	{
		$this->assertFalse($this->column->getAutoIncrement());
	}

	public function testAutoIncrement()
	{
		$this->column->setAutoIncrement(true);
		$this->runAssert('user_id INT AUTO_INCREMENT');
	}

	public function testDefault()
	{
		$column = new \Peyote\Column('create_date', 'TIMESTAMP', array(
			'default' => 'CURRENT_TIMESTAMP'
		));

		$this->assertSame('CURRENT_TIMESTAMP', $column->getDefault());
		$this->runAssert('create_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP', $column);
	}

	public function testSerial()
	{
		$column = new \Peyote\Column('user_id', 'serial');

		$this->assertTrue($column->isPrimaryKey());
		$this->runAssert('user_id INT UNSIGNED NOT NULL AUTO_INCREMENT', $column);
	}

	/**
	 * This column is ridiculous, please don't use this...
	 */
	public function testConstructorOptions()
	{
		$column = new \Peyote\Column('user_id', 'INT', array(
			'is_null' => false,
			'length' => 8,
			'options' => array('UNSIGNED', 'ZEROFILL'),
			'auto_increment' => true,
			'default' => '0'
		));

		$expected = 'user_id INT(8) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 AUTO_INCREMENT';
		$this->runAssert($expected, $column);
	}

	private function runAssert($expected, $column = null)
	{
		if ($column === null)
		{
			$column = $this->column;
		}

		$this->assertSame($expected, $column->compile());
	}

}
