<?php

namespace Peyote;

/**
 * A class for Having statements.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Having extends \Peyote\Base
{
	/**
	 * @var array  An array of having clauses
	 */
	private $having = array();

	/**
	 * Sets the ORDER BY for the query.
	 *
	 * @param  string $column  The column name
	 * @param  string $value   The having value
	 * @return $this
	 */
	public function having($column, $op, $value)
	{
		$this->having[] = "{$column} {$op} {$this->quote($value)}";

		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		// Make sure there are column
		if (empty($this->having) === true)
		{
			return "";
		}

		return "HAVING ".implode(", ", $this->having);
	}
}
