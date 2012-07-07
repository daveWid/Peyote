<?php

namespace Peyote;

/**
 * An abstract starting class for the ORDER BY and GROUP BY classes.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Sort implements \Peyote\Builder
{
	/**
	 * @var array  The order clauses.
	 */
	protected $clauses = array();

	/**
	 * Gets the type of sorting query we are running.
	 *
	 * @return string
	 */
	abstract public function getType();

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

		return $this->getType()." ".join(", ", $sql);
	}

}
