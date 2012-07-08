<?php

namespace Peyote;

/**
 * Building an INSERT query.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Insert extends \Peyote\Query
{
	/**
	 * @var array  A list of columns to insert
	 */
	private $columns = array();

	/**
	 * @var array  A list of values to insert
	 */
	private $values = array();

	/**
	 * Specify the columns to insert.
	 *
	 * @param  array $columns  The columns
	 * @return \Peyote\Insert
	 */
	public function columns(array $columns)
	{
		$this->columns = $columns;
		return $this;
	}

	/**
	 * The values to add to the insert statement.
	 *
	 * @param  array $values  The column values
	 * @return \Peyote\Insert
	 */
	public function values(array $values)
	{
		$this->values[] = $values;
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		$sql = array("INSERT INTO");
		$sql[] = $this->table;
		$sql[] = "(".join(', ', $this->columns).")";
		$sql[] = "VALUES";

		$values = array();
		foreach ($this->values as $row)
		{
			$placeholders = array_fill(0, count($row), "?");
			$values[] = "(".join(", ", $placeholders).")";
			$this->params = array_merge($this->params, $row);
		}

		$sql[] = join(', ', $values);

		return join(' ', $sql);
	}

}
