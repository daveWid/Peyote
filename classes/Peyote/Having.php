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
	 * Adds a clause with AND.
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function andHaving($column, $op, $value)
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
	public function orHaving($column, $op, $value)
	{
		$this->addGroup("OR", array($column, $op, $value));
		return $this;
	}

	/**
	 * Alias of and_having_open()
	 *
	 * @return  $this
	 */
	public function havingOpen()
	{
		return $this->andHavingOpen();
	}

	/**
	 * Opens a new "AND HAVING (...)" grouping.
	 *
	 * @return  $this
	 */
	public function andHavingOpen()
	{
		$this->addGroup("AND", "(");
		return $this;
	}

	/**
	 * Opens a new "OR HAVING (...)" grouping.
	 *
	 * @return  $this
	 */
	public function orHavingOpen()
	{
		$this->addGroup("OR", "(");
		return $this;
	}

	/**
	 * Alias for and_having_close()
	 *
	 * @return  $this
	 */
	public function havingClose()
	{
		return $this->andHavingClose();
	}

	/**
	 * Closes an open "AND (...)" grouping.
	 *
	 * @return  $this
	 */
	public function andHavingClose()
	{
		$this->addGroup("AND", ")");
		return $this;
	}

	/**
	 * Closes an open "OR (...)" grouping.
	 *
	 * @return  $this
	 */
	public function orHavingClose()
	{
		$this->addGroup("OR", ")");
		return $this;
	}

	/**
	 * Get the methods that this class will pass through.
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return array(
			'addHaving',
			'orHaving',
			'havingOpen',
			'andHavingOpen',
			'orHavingOpen',
			'havingClose',
			'andHavingClose',
			'orHavingClose'
		);
	}

}
