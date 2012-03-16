<?php

namespace Peyote;

/**
 * A class that holds all of the WHERE statments.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Where extends \Peyote\WhereCondition
{
	/**
	 * Gets the type of condition
	 *
	 * @param string
	 */
	public function type()
	{
		return "WHERE";
	}

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
		$this->add_group("AND", array($column, $op, $value));
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
		$this->add_group("OR", array($column, $op, $value));
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
		$this->add_group("AND", "(");
		return $this;
	}

	/**
	 * Opens a new "OR WHERE (...)" grouping.
	 *
	 * @return  $this
	 */
	public function or_where_open()
	{
		$this->add_group("OR", "(");
		return $this;
	}

	/**
	 * Alias for and_where_close()
	 *
	 * @return  $this
	 */
	public function where_close()
	{
		return $this->and_where_close();
	}

	/**
	 * Closes an open "AND (...)" grouping.
	 *
	 * @return  $this
	 */
	public function and_where_close()
	{
		$this->add_group("AND", ")");
		return $this;
	}

	/**
	 * Closes an open "OR (...)" grouping.
	 *
	 * @return  $this
	 */
	public function or_where_close()
	{
		$this->add_group("OR", ")");
		return $this;
	}

}
