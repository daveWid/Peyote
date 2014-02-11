<?php

namespace Peyote;

/**
 * The Create table class
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Create extends Drop
{
	/**
	 * @var array
	 */
	private $columns = array();

	/**
	 * @var array
	 */
	private $primary_key = array();

	/**
	 * @var string
	 */
	private $engine = "MyISAM";

	/**
	 * @var string
	 */
	private $charset = "utf8";

	/**
	 * @return string  The column list
	 */
	public function getColumns()
	{
		return $this->columns;
	}

	/**
	 * @param array $columns   The columns to set.
	 * @return \Peyote\Create  $this
	 */
	public function setColumns(array $columns)
	{
		$this->columns = $columns;
		return $this;
	}

	/**
	 * @return array  A list of primary keys
	 */
	public function getPrimaryKey()
	{
		return $this->primary_key;
	}

	/**
	 * @param array|string     The key(s) to set
	 * @return \Peyote\Create  $this
	 */
	public function setPrimaryKey($keys)
	{
		if (\is_string($keys))
		{
			$keys = array($keys);
		}

		$this->primary_key = $keys;
		return $this;
	}

	/**
	 * @param string $key      The name of the primary key to add
	 * @return \Peyote\Create  $this
	 */
	public function addPrimaryKey($key)
	{
		if ( ! in_array($key, $this->primary_key))
		{
			$this->primary_key[] = $key;
		}

		return $this;
	}

	/**
	 * @return string  The database engine (MyISAM, InnoDB)
	 */
	public function getEngine()
	{
		return $this->engine;
	}

	/**
	 * @param  string $engine  The database engine
	 * @return \Peyote\Create  $this
	 */
	public function setEngine($engine)
	{
		$this->engine = $engine;
		return $this;
	}

	/**
	 * @return string  The character set
	 */
	public function getCharset()
	{
		return $this->charset;
	}

	/**
	 * @param  string $charset  The character set.
	 * @return \Peyote\Create   $this
	 */
	public function setCharset($charset)
	{
		$this->charset = $charset;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function compile()
	{
		$query = array("CREATE", $this->getType());

		if ($this->getIfExists() === true)
		{
			$query[] = "IF NOT EXISTS";
		}

		$query[] = $this->getTable();
		$query[] = "(";

		$columns = array();
		foreach ($this->getColumns() as $column)
		{
			if ($column instanceof \Peyote\Column)
			{
				if ($column->isPrimaryKey())
				{
					$this->addPrimaryKey($column->getName());
				}

				$column = $column->compile();
			}

			$columns[] = $column;
		}

		if ( ! empty($this->primary_key))
		{
			$columns[] = "PRIMARY KEY (".join(', ', $this->primary_key).')';
		}

		$query[] = join(', ', $columns);
		$query[] = ")";

		$query[] = 'ENGINE='.$this->getEngine();
		$query[] = 'DEFAULT CHARSET='.$this->getCharset();

		return join(' ', $query);
	}

}
