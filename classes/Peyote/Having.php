<?php

namespace Peyote;

/**
 * A class that holds all of the HAVING statments.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Having extends \Peyote\Condition
{
	/**
	 * Gets the type of condition
	 *
	 * @param string
	 */
	public function type()
	{
		return "HAVING";
	}

	/**
	 * Alias for and_where
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function having($column, $op, $value)
	{
		return $this->and_having($column, $op, $value);
	}

	/**
	 * Adds a clause with AND.
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function and_having($column, $op, $value)
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
	public function or_having($column, $op, $value)
	{
		$this->add_group("OR", array($column, $op, $value));
		return $this;
	}

	/**
	 * Alias of and_having_open()
	 *
	 * @return  $this
	 */
	public function having_open()
	{
		return $this->and_having_open();
	}

	/**
	 * Opens a new "AND HAVING (...)" grouping.
	 *
	 * @return  $this
	 */
	public function and_having_open()
	{
		$this->add_group("AND", "(");
		return $this;
	}

	/**
	 * Opens a new "OR HAVING (...)" grouping.
	 *
	 * @return  $this
	 */
	public function or_having_open()
	{
		$this->add_group("OR", "(");
		return $this;
	}

	/**
	 * Alias for and_having_close()
	 *
	 * @return  $this
	 */
	public function having_close()
	{
		return $this->and_having_close();
	}

	/**
	 * Closes an open "AND (...)" grouping.
	 *
	 * @return  $this
	 */
	public function and_having_close()
	{
		$this->add_group("AND", ")");
		return $this;
	}

	/**
	 * Closes an open "OR (...)" grouping.
	 *
	 * @return  $this
	 */
	public function or_having_close()
	{
		$this->add_group("OR", ")");
		return $this;
	}

}
