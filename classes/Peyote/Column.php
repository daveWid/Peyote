<?php

namespace Peyote;

/**
 * A class to define columns
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Column implements Builder
{
	/**
	 * @var string  The name of the column.
	 */
	private $name;

	/**
	 * @var string  The data type for the column.
	 */
	private $type;

	/**
	 * @var boolean  Can this column be null?
	 */
	private $is_null = null;

	/**
	 * @var string  The length of a field
	 */
	private $length = null;

	/**
	 * @var array    Column options (UNSIGNED, ZEROFILL, etc...)
	 */
	private $options = array();

	/**
	 * @var boolean  Is this an auto increment column?
	 */
	private $auto_increment = false;

	/**
	 * @var string   The default value
	 */
	private $default = null;

	/**
	 * @var boolean  Is this column a primary key?
	 */
	private $primary_key = false;

	/**
	 * Below are the keys that can be used in the options parameters
	 *
	 * Key            | Type    | Description
	 * ---------------|---------|-----------------------------------
	 * is_null        | boolean | Can this column be null?
	 * length         | string  | The length of a field (number fields)
	 * options        | array   | A list of data type options (UNSIGNED, ZEROFILL, etc...)
	 * auto_increment | boolean | Auto Increment the column?
	 * default        | string  | The default value for the column
	 *
	 * @param string $name    The name of the column
	 * @param string $type    The type of column
	 * @param array  $options See above for column options that can be set.
	 */
	public function __construct($name, $type, array $options = array())
	{
		if ( ! empty($options))
		{
			$list = array(
				'is_null' => 'setIsNull',
				'length' => 'setLength',
				'options' => 'setOptions',
				'auto_increment' => 'setAutoIncrement',
				'default' => 'setDefault',
			);

			foreach ($list as $key => $fn)
			{
				if (isset($options[$key]))
				{
					$this->{$fn}($options[$key]);
				}
			}
		}

		$this->setName($name);
		$this->setType($type);
	}

	/**
	 * @return string  The column name
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param  string $name    The new column name
	 * @return \Peyote\Column  $this
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string  The current data type
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param  string $type    The data type
	 * @return \Peyote\Column  $this
	 */
	public function setType($type)
	{
		if (\strtolower($type) === 'serial')
		{
			$type = "INT";
			$this->primary_key = true;
			$this->setIsNull(false);
			$this->setAutoIncrement(true);
			$this->addOption('UNSIGNED');
		}

		$this->type = $type;

		return $this;
	}

	/**
	 * @return boolean|null
	 */
	public function getIsNull()
	{
		return $this->is_null;
	}

	/**
	 * @param  boolean $is_null  Should this column be null?
	 * @return \Peyote\Column
	 */
	public function setIsNull($is_null)
	{
		$this->is_null = (boolean) $is_null;
		return $this;
	}

	/**
	 * @return string  The length of the column
	 */
	public function getLength()
	{
		return $this->length;
	}

	/**
	 * @param  string $length  The field length
	 * @return \Peyote\Column  $this
	 */
	public function setLength($length)
	{
		$this->length = $length;
		return $this;
	}

	/**
	 * @return array  Column options
	 */
	public function getOptions()
	{
		return $this->options;
	}

	/**
	 * @param  array $options  The column options.
	 * @return \Peyote\Column  $this
	 */
	public function setOptions(array $options)
	{
		$this->options = $options;
		return $this;
	}

	/**
	 * @param  string $option The option to add
	 * @return \Peyote\Column
	 */
	public function addOption($option)
	{
		if ( ! \in_array($option, $this->options))
		{
			$this->options[] = $option;
		}

		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getAutoIncrement()
	{
		return $this->auto_increment;
	}

	/**
	 * @param  boolean $flag  Set auto increment?
	 * @return \Peyote\Column
	 */
	public function setAutoIncrement($flag)
	{
		$this->auto_increment = (boolean) $flag;
		return $this;
	}

	/**
	 * @return mixed  The default value or null if it hasn't been set
	 */
	public function getDefault()
	{
		return $this->default;
	}

	/**
	 * @param  string $value The default value
	 * @return \Peyote\Column
	 */
	public function setDefault($value)
	{
		$this->default = $value;
		return $this;
	}

	/**
	 * @return boolean  Is this a primary key column?
	 */
	public function isPrimaryKey()
	{
		return $this->primary_key;
	}

	/**
	 * {@inheritDoc}
	 */
	public function compile()
	{
		$sql = array($this->name);

		$sql[] = $this->length !== null ?
			$this->type."(".$this->length.")" :
			$this->type;

		if ( ! empty($this->options))
		{
			$sql = \array_merge($sql, $this->options);
		}

		if ($this->is_null !== null)
		{
			$sql[] = $this->is_null === true ? "NULL" : "NOT NULL";
		}

		if ($this->default !== null)
		{
			$sql[] = "DEFAULT {$this->default}";
		}

		if ($this->auto_increment === true)
		{
			$sql[] = "AUTO_INCREMENT";
		}

		return join(' ', $sql);
	}
}
