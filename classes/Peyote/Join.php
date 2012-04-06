<?php

namespace Peyote;

/**
 * A class to help build JOIN statements.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Join implements \Peyote\Builder
{
	/**
	 * @var array  A list of joins
	 */
	private $joins = array();

	/**
	 * @var array  The join that is in queue
	 */
	private $in_queue = null;

	/**
	 * Starts the join statement
	 *
	 * @param  string $table  The table to join
	 * @param  string $type   The type of join
	 * @return $this
	 */
	public function add_join($table, $type = null)
	{
		if ($type !== null)
		{
			$type = strtoupper((string) $type);
		}

		if ($type === "CROSS")
		{
			$this->joins[] = array($table, $type);
			$this->_in_queue = null;
		}
		else
		{
			$this->in_queue = array($table, $type);
		}

		return $this;
	}

	/**
	 * The on statement for joins. Throws an exception if join() isn't called
	 * first.
	 *
	 * @param  string $column1  The first column
	 * @param  string $op       The operator
	 * @param  string $column2  The second column
	 * @return $this
	 */
	public function on($column1, $op, $column2)
	{
		if ($this->in_queue === null)
		{
			throw new \Peyote\Exception("The ON statement needs an associated JOIN");
		}

		$using = array("ON",$column1,$op,$column2);
		$this->joins[] = array_merge($this->in_queue, $using);

		$this->in_queue = null;
		return $this;
	}

	/**
	 * A list of on commands using AND to combine them.
	 *
	 * @param  array $statements The on statements
	 * @return $this
	 */
	public function on_and(array $statements)
	{
		if ($this->in_queue === null)
		{
			throw new \Peyote\Exception("The ON...AND statement needs an associated JOIN");
		}

		$using = array("ON", $statements);
		$this->joins[] = array_merge($this->in_queue, $using);

		$this->in_queue = null;
		return $this;
	}

	/**
	 * The USING statement for join. Throws an exception if join() isn't called
	 * first.
	 *
	 * @throws \Peyote\Exception
	 *
	 * @param  string $column  The column to join on
	 * @return $this
	 */
	public function using($column)
	{
		if ($this->in_queue === null)
		{
			throw new \Peyote\Exception("The USING statement needs an associated JOIN");
		}

		$using = array("USING({$column})");
		$this->joins[] = array_merge($this->in_queue, $using);

		$this->in_queue = null;
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		if (empty($this->joins) === true)
		{
			return "";
		}

		$joins = array();
		foreach ($this->joins as $row)
		{
			$sql = array();

			// Add modifier if necessary
			if ($row[1] !== null)
			{
				$sql[] = strtoupper($row[1]);
			}

			$sql[] ="JOIN";
			if (is_array($row[0]))
			{
				list($column, $alias) = $row[0];
				$sql[] = ($column instanceof \Peyote\Select) ? "( {$column} )" : $column;
				$sql[] = "AS {$alias}";
			}
			else
			{
				$sql[] = ($row[0] instanceof \Peyote\Select) ? "( {$row[0]} )" : $row[0];
			}

			$rest = array_slice($row, 2);

			if (isset($rest[1]) AND is_array($rest[1]))
			{
				// ON ... AND
				$and = array(array_shift($rest));
				foreach($rest[0] as $r)
				{
					$and[] = implode(" ", $r);
					$and[] = "AND";
				}

				array_pop($and);
				$rest = $and;
			}

			$sql = array_merge($sql, $rest);
			$joins[] = implode(" ", $sql);
		}

		return implode(" ", $joins);
	}
}
