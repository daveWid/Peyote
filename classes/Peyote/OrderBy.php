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
	public function order_by($column, $direction = null)
	{
		$this->columns[] = array($column, $direction);

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
		if (count($this->columns) === 0)
		{
			return "";
		}

		$order = array();
		foreach ($this->columns as $row)
		{
			list($column, $direction) = $row;

			if ($direction)
			{
				$direction = " ".strtoupper($direction);
			}

			$order[] = $column.$direction;
		}

		return "ORDER BY ".implode(", ", $order);
	}
}
