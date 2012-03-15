<?php

namespace Peyote;

/**
 * A class that holds all of the where statements. A lot of this was taken from
 * the Kohana_Database_Query_Builder_Where class.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Where extends \Peyote\Base
{
	/**
	 * @var array  The different where parts.
	 */
	private $where = array();

	/**
	 * Alias for and_where
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function where($column, $op, $value)
	{
		return $this->and_where($column, $op, $value);
	}

	/**
	 * Adds a clause with AND.
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function and_where($column, $op, $value)
	{
		$this->where[] = array("AND" => array($column, $op, $value));

		return $this;
	}

	/**
	 * Adds a clause with OR.
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return $this
	 */
	public function or_where($column, $op, $value)
	{
		$this->where[] = array("OR" => array($column, $op, $value));

		return $this;
	}

	/**
	 * Alias of and_where_open()
	 *
	 * @return  $this
	 */
	public function where_open()
	{
		return $this->and_where_open();
	}

	/**
	 * Opens a new "AND WHERE (...)" grouping.
	 *
	 * @return  $this
	 */
	public function and_where_open()
	{
		$this->where[] = array("AND" => "(");

		return $this;
	}

	/**
	 * Opens a new "OR WHERE (...)" grouping.
	 *
	 * @return  $this
	 */
	public function or_where_open()
	{
		$this->where[] = array("OR" => "(");

		return $this;
	}

	/**
	 * Alias for and_where_close()
	 *
	 * @return  $this
	 */
	public function where_close()
	{
		return $this->and_where_close();
	}

	/**
	 * Closes an open "AND (...)" grouping.
	 *
	 * @return  $this
	 */
	public function and_where_close()
	{
		$this->where[] = array("AND" => ")");

		return $this;
	}

	/**
	 * Closes an open "OR (...)" grouping.
	 *
	 * @return  $this
	 */
	public function or_where_close()
	{
		$this->where[] = array("OR" => ")");

		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		if (empty($this->where) === true)
		{
			return "";
		}

		$last = null;
		$sql = array();
		foreach ($this->where as $group)
		{
			foreach ($group as $type => $condition)
			{
				if ($condition === "(")
				{
					if (empty($sql) === false AND $last !== "(")
					{
						$sql[] = $type;
					}

					$sql[] = "(";
				}
				else if($condition === ")")
				{
					$sql[] = ")";
				}
				else
				{
					if (empty($sql) === false AND $last !== "(")
					{
						$sql[] = $type;
					}

					list($column, $op, $value) = $condition;

					if ($op === "BETWEEN")
					{
						$sql[] = "{$column} {$op} {$this->quote($value[0])} AND {$this->quote($value[1])}";
					}
					else
					{
						$sql[] = "{$column} {$op} {$this->quote($value)}";
					}
				}

				$last = $condition;
			}
		}

		return "WHERE ".implode(" ", $sql);
	}
}
