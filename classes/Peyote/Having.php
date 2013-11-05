<?php

namespace Peyote;

/**
 * A class to build HAVING clauses.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Having extends \Peyote\Condition implements \Peyote\Mixin
{
	/**
	 * Gets the type of sorting query we are running.
	 *
	 * @return string
	 */
	public function getType()
	{
		return "HAVING";
	}

	/**
	 * Builds an AND HAVING clause.
	 *
	 * @param  string  $column  The column name
	 * @param  string  $op      The comparison operator
	 * @param  mixed   $value   The value to bind
	 * @param  boolean $isParam Is this a parameter (replace value with ? if true)
	 * @return \Peyote\Where
	 */
	public function andHaving($column, $op, $value, $isParam = true)
	{
		$this->clauses[] = array("AND", $column, $op, $value, $isParam);
		return $this;
	}

	/**
	 * Builds an OR HAVING clause.
	 *
	 * @param  string  $column  The column name
	 * @param  string  $op      The comparison operator
	 * @param  mixed   $value   The value to bind
	 * @param  boolean $isParam Is this a parameter (replace value with ? if true)
	 * @return \Peyote\Where
	 */
	public function orHaving($column, $op, $value, $isParam = true)
	{
		$this->clauses[] = array("OR", $column, $op, $value, $isParam);
		return $this;
	}

	/**
	 * Gets all of the methods that should be passed as "mixin" methods.
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return array('andHaving', 'orHaving');
	}

}
