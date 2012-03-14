<?php

namespace Peyote;

/**
 * A class that holds all of the where statements. A lot of this was taken from
 * the Kohana_Database_Query_Builder_Where class.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Where extends \Peyote\Base
{
	/**
	 * @var array  The different where parts.
	 */
	private $where = array();

	/**
	 * Alias for and_where
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function where($column, $op, $value)
	{
		return $this->and_where($column, $op, $value);
	}

	/**
	 * Adds a clause with AND.
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function and_where($column, $op, $value)
	{
		$this->where[] = "AND";
		$this->where[] = "{$column} {$op} {$this->quote($value)}";

		return $this;
	}

	/**
	 * Adds a clause with OR.
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function or_where($column, $op, $value)
	{
		$this->where[] = "OR";
		$this->where[] = "{$column} {$op} {$this->quote($value)}";

		return $this;
	}

	/**
	 * Alias of and_where_open()
	 *
	 * @return  $this
	 */
	public function where_open()
	{
		return $this->and_where_open();
	}

	/**
	 * Opens a new "AND WHERE (...)" grouping.
	 *
	 * @return  $this
	 */
	public function and_where_open()
	{
		$this->where[] = "AND(";

		return $this;
	}

	/**
	 * Opens a new "OR WHERE (...)" grouping.
	 *
	 * @return  $this
	 */
	public function or_where_open()
	{
		$this->where[] = "OR(";

		return $this;
	}

	/**
	 * Closes an open "(...)" grouping.
	 *
	 * @return  $this
	 */
	public function where_close()
	{
		$this->where[] = ")";

		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		if (empty($this->where) === true)
		{
			return "";
		}

		array_shift($this->where);
		return "WHERE ".implode("", $this->where);
	}
}
