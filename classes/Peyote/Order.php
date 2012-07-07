<?php

namespace Peyote;

/**
 * The ORDER BY clause builder.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Order implements \Peyote\Builder
{
	/**
	 * @var array  The order clauses.
	 */
	private $clauses = array();

	/**
	 * @var type string  The "type" of clause this is.
	 */
	protected $type = "ORDER BY";

	/**
	 * Orders a result with an optional direction.
	 *
	 * @param  string $column     The column name
	 * @param  string $direction  The direction to sort on
	 * @return \Peyote\Order
	 */
	public function orderBy($column, $direction = null)
	{
		$this->clauses[] = array($column, $direction);
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		if (empty($this->clauses))
		{
			return "";
		}

		$sql = array();
		foreach ($this->clauses as $clause)
		{
			list($column, $direction) = $clause;

			if ($direction === null)
			{
				$sql[] = $column;
			}
			else
			{
				$sql[] = "{$column} {$direction}";
			}
		}

		return $this->type." ".join(", ", $sql);
	}

}
