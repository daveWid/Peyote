<?php

namespace Peyote;

/**
 * A base class for condition statments. The WHERE class and HAVING class
 * will extend this.
 * 
 * A lot of this was taken from the Kohana_Database_Query_Builder_Where class.
 * 
 * @link http://kohanaframework.org/3.2/guide/api/Database_Query_Builder_Where
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Condition extends \Peyote\Base
{
	/**
	 * Gets the type of condition
	 *
	 * @param string
	 */
	abstract public function type();

	/**
	 * @var array  The groupings.
	 */
	protected $groups = array();

	/**
	 * Adds a group.
	 */
	protected function addGroup($type, $value)
	{
		$this->groups[] = array($type => $value);
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		if (empty($this->groups) === true)
		{
			return "";
		}

		$last = null;
		$sql = array();
		foreach ($this->groups as $group)
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
					else if ($op === "AGAINST")
					{
						$sql[] = "MATCH({$column}) AGAINST({$this->quote($value)})";
					}
					else
					{
						$sql[] = "{$column} {$op}";

						if ($value instanceof \Peyote\Select)
						{
							$sql[] = "(";
							$sql[] = $this->quote($value);
							$sql[] = ")";
						}
						else
						{
							$sql[] = $this->quote($value);
						}
					}
				}

				$last = $condition;
			}
		}

		return $this->type()." ".implode(" ", $sql);
	}
}
