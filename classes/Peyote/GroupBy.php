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
	 * Empty constructor to avoid php4 compatibility problems.
	 */
	public function __construct(){}

	/**
	 * @var string  The column name to group by
	 */
	private $columns = null;

	/**
	 * Adds a group by clause.
	 *
	 * @param  string $column  A list of columns to group by
	 * @return $this
	 */
	public function group_by($columns)
	{
		$columns = func_get_args();

		$this->columns = implode(", ", $columns);
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		return ($this->columns === null) ? "" : "GROUP BY {$this->columns}";
	}
}
