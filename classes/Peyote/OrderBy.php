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
	 * @var string  The column used in ordering
	 */
	private $column = null;

	/**
	 * @var string  The direction used in sorting
	 */
	private $direction = "ASC";

	/**
	 * Sets the ORDER BY for the query.
	 *
	 * @param  string $column     The column name
	 * @param  string $direction  The sort direction
	 * @return $this
	 */
	public function order_by($column, $direction = "ASC")
	{
		$this->column = $column;
		$this->direction = ($direction === "DESC") ? "DESC" : "ASC";
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		return ($this->column === null) ?
			"" :
			"ORDER BY {$this->column} {$this->direction}";
	}
}
