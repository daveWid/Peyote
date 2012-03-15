<?php

namespace Peyote;

/**
 * A class that builds INSERT statments.
 *
 * @link       http://dev.mysql.com/doc/refman/5.0/en/insert.html
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Insert extends \Peyote\Base
{
	/**
	 * @var array  The column list
	 */
	private $columns = array();

	/**
	 * @var array  Associative array of column => value pairs to insert.
	 */
	private $values = array();

	/**
	 * @var boolean  Set the ignore flag?
	 */
	private $ignore = false;

	/**
	 * @var \Peyote\Select  Any select query to tack on
	 */
	private $select = null;

	/**
	 * @var \Peyote\Update  Update statement if duplicate key is found
	 */
	private $duplicate = null;

	/**
	 * Create a new Insert instance.
	 *
	 * @param mixed $table   The table name OR array($table, $alias)
	 */
	public function __construct($table = "")
	{
		$this->table($table);
	}

	/**
	 * Ignore duplicate rows?
	 *
	 * @param  boolean $flag  ignor flag
	 * @return $this
	 */
	public function ignore($flag = true)
	{
		$this->ignore = $flag;
		return $this;
	}

	/**
	 * A list of columns for the insert statement.
	 *
	 * @param  array $columns  The column list
	 * @return $this
	 */
	public function columns(array $columns)
	{
		$this->columns = $columns;
		return $this;
	}

	/**
	 * Sets the values for an insert statement. If a select statement has already
	 * been added, this will throw an error as the two types can not mix.
	 *
	 * @throws \Peyote\Exception
	 *
	 * @param array $data  An array of column/value pairs
	 */
	public function values(array $data)
	{
		if ($this->select !== null)
		{
			throw new \Peyote\Exception("Attempting to set INSERT values when a SELECT statement has already been set");
		}
		
		if (is_array($data[0]) === false)
		{
			$data = array($data);
		}

		foreach ($data as $row)
		{
			$this->values[] = $row;
		}

		return $this;
	}

	/**
	 * Add a SELECT statement to the query. If values are already set, this
	 * will throw an exception because you can not mix VALUES and SELECT.
	 *
	 * @throws \Peyote\Exception
	 *
	 * @param  \Peyote\Select $select  The select statement
	 * @return $this
	 */
	public function select(\Peyote\Select $select)
	{
		if (empty($this->values) === false)
		{
			throw new \Peyote\Exception("Attempting to set INSERT...SELECT when values are already set");
		}
		
		$this->select = $select;
		return $this;
	}

	/**
	 * Sets an update statement to run in case a key is already found.
	 *
	 * @param  \Peyote\Update $update  The update statement
	 * @return $this
	 */
	public function on_duplicate_key(\Peyote\Update $update)
	{
		$this->duplicate = $update;
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		$sql = array("INSERT");

		// Ignore??
		if ($this->ignore)
		{
			$sql[] = "IGNORE";
		}

		$sql[] = "INTO";
		$sql[] = $this->table();

		// Columns?
		if (empty($this->columns) === false)
		{
			$sql[] = "(".implode(",", $this->columns).")";
		}

		// Values or Select
		if ($this->select !== null)
		{
			$sql[] = $this->select->compile();
		}
		else
		{
			$data = array();
			foreach ($this->values as $row)
			{
				$row = array_map(array($this, "quote") , $row);
				$data[] = "(".implode(", ", $row).")";
			}

			$sql[] = "VALUES";
			$sql[] = implode(", ", $data);
		}

		// On duplicate
		if ($this->duplicate !== null)
		{
			$sql[] = "ON DUPLICATE KEY";
			$sql[] = $this->duplicate->compile();
		}

		return implode(" ", $sql);
	}

}
