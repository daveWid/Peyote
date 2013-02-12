<?php

namespace Peyote;

/**
 * The JOIN clause builder.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Join implements \Peyote\Builder, \Peyote\Mixin
{
	/**
	 * @var array  The internal list of joins
	 */
	private $joins = array();

	/**
	 * @var array  The currently active join
	 */
	private $active_join = null;

	/**
	 * Add a join.
	 *
	 * @param  string $table  The table name
	 * @param  string $type   The type of join
	 * @return \Peyote\Join
	 */
	public function addJoin($table, $type = null)
	{
		$this->active_join = array($table, $type);
		return $this;
	}

	/**
	 * Creates an ON join.
	 *
	 * @throws \Peyote\Exception
	 *
	 * @param  string $column1  The column from the first table
	 * @param  string $op       The operator
	 * @param  string $column2  The column from the second table
	 * @return \Peyote\Join
	 */
	public function on($column1, $op, $column2)
	{
		if ($this->active_join === null)
		{
			throw new \Peyote\Exception("You need to start a join before calling \Peyote\Join::on()");
		}

		list($table, $type) = $this->active_join;
		$this->active_join = null;
		$this->joins[] = array("ON", $table, $type, $column1, $op, $column2);
		return $this;
	}

	/**
	 * Create a USING join.
	 *
	 * @throws \Peyote\Exception
	 *
	 * @param  string $column  The column to join on
	 * @return \Peyote\Join
	 */
	public function using($column)
	{
		if ($this->active_join === null)
		{
			throw new \Peyote\Exception("You need to start a join before calling \Peyote\Join::using()");
		}

		list($table, $type) = $this->active_join;
		$this->active_join = null;
		$this->joins[] = array("USING", $table, $type, $column);
		return $this;
	}

	/**
	 * Gets all of the methods that should be passed as "mixin" methods.
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return array('on', 'using');
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		if (empty($this->joins))
		{
			return "";
		}

		$joins = array();
		foreach ($this->joins as $join)
		{
			if ($join[0] === "ON")
			{
				$joins[] = $this->compileOn($join);
			}
			else
			{
				$joins[] = $this->compileUsing($join);
			}
		}

		return join(", ", $joins);
	}

	/**
	 * Compiles an ON join.
	 *
	 * @param  array $join  The on join to compile
	 * @return string
	 */
	private function compileOn(array $join)
	{
		$sql = array();
		list($on, $table, $type, $c1, $op, $c2) = $join;

		if ($type !== null)
		{
			$sql[] = $type;
		}

		array_push($sql, "JOIN", $table, "ON", $c1, $op, $c2);
		return join(' ', $sql);
	}

	/**
	 * Compiles a JOIN USING clause
	 *
	 * @param  array $join  The join to compile
	 * @return string
	 */
	private function compileUsing(array $join)
	{
		$sql = array();
		list($using, $table, $type, $column) = $join;

		if ($type !== null)
		{
			$sql[] = $type;
		}

		array_push($sql, "JOIN", $table, "USING({$column})");
		return join(' ', $sql);
	}

}
