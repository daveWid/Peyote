<?php

namespace Peyote;

/**
 * An abstract starting class for the WHERE and HAVING classes.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Condition implements \Peyote\Builder
{
	/**
	 * @var array  The order clauses.
	 */
	protected $clauses = array();

	/**
	 * @var array  A list of bound parameters.
	 */
	protected $params = array();

	/**
	 * Gets the type of sorting query we are running.
	 *
	 * @return string
	 */
	abstract public function getType();

	/**
	 * Gets the list of bound parameters.
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
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
			list($type, $column, $op, $value) = $clause;
			$sql[] = $type;
			$sql[] = "{$column} {$op} ?";
			$this->params[] = $value;
		}

		array_shift($sql);

		return $this->getType()." ".join(" ", $sql);
	}

}
