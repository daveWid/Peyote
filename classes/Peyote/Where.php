<?php

namespace Peyote;

/**
 * A class that holds all of the WHERE statments.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Where extends \Peyote\Condition
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
	 * Adds a clause with AND.
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function andWhere($column, $op, $value)
	{
		$this->addGroup("AND", array($column, $op, $value));
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
	public function orWhere($column, $op, $value)
	{
		$this->addGroup("OR", array($column, $op, $value));
		return $this;
	}

	/**
	 * Alias of and_where_open()
	 *
	 * @return  $this
	 */
	public function whereOpen()
	{
		return $this->andWhereOpen();
	}

	/**
	 * Opens a new "AND WHERE (...)" grouping.
	 *
	 * @return  $this
	 */
	public function andWhereOpen()
	{
		$this->addGroup("AND", "(");
		return $this;
	}

	/**
	 * Opens a new "OR WHERE (...)" grouping.
	 *
	 * @return  $this
	 */
	public function or_where_open()
	{
		$this->addGroup("OR", "(");
		return $this;
	}

	/**
	 * Alias for and_where_close()
	 *
	 * @return  $this
	 */
	public function whereClose()
	{
		return $this->andWhereClose();
	}

	/**
	 * Closes an open "AND (...)" grouping.
	 *
	 * @return  $this
	 */
	public function andWhereClose()
	{
		$this->addGroup("AND", ")");
		return $this;
	}

	/**
	 * Closes an open "OR (...)" grouping.
	 *
	 * @return  $this
	 */
	public function orWhereClose()
	{
		$this->addGroup("OR", ")");
		return $this;
	}

}
