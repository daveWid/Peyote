<?php

namespace Peyote;

/**
 * A class for GROUP BY statements.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class GroupBy implements \Peyote\Builder
{
	/**
	 * @var string  The column name to group by
	 */
	private $column = null;

	/**
	 * Adds a group by clause.
	 *
	 * @param  string $column  The column to group by
	 * @return $this
	 */
	public function group_by($column)
	{
		$this->column = $column;
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		return ($this->column === null) ? "" : "GROUP BY {$this->column}";
	}
}
