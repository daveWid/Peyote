<?php

namespace Peyote;

/**
 * A class for ORDER BY statements.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class OrderBy implements \Peyote\Builder
{
	/**
	 * @var array  An array of string holding column DIRECTION
	 */
	private $columns = array();

	/**
	 * Sets the ORDER BY for the query.
	 *
	 * @param  string $column     The column name
	 * @param  string $direction  The sort direction
	 * @return $this
	 */
	public function order_by($column, $direction = "ASC")
	{
		$direction = ($direction === "DESC") ? "DESC" : "ASC";
		$this->columns[] = "{$column} {$direction}";

		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		return (count($this->columns) > 0) ?
			"ORDER BY ".implode(", ", $this->columns) :
			"";
	}
}
