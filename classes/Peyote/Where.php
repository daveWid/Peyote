<?php

namespace Peyote;

/**
 * A class to build WHERE clauses.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Where extends \Peyote\Condition implements \Peyote\Mixin
{
	/**
	 * Gets the type of sorting query we are running.
	 *
	 * @return string
	 */
	public function getType()
	{
		return "WHERE";
	}

	/**
	 * Builds an AND WHERE clause.
	 *
	 * @param  string  $column  The column name
	 * @param  string  $op      The comparison operator
	 * @param  mixed   $value   The value to bind
	 * @param  boolean $isParam Is this a parameter (replace value with ? if true)
	 * @return \Peyote\Where
	 */
	public function andWhere($column, $op, $value, $isParam = true)
	{
		$this->clauses[] = array("AND", $column, $op, $value, $isParam);
		return $this;
	}

	/**
	 * Builds an OR WHERE clause.
	 *
	 * @param  string  $column  The column name
	 * @param  string  $op      The comparison operator
	 * @param  mixed   $value   The value to bind
	 * @param  boolean $isParam Is this a parameter (replace value with ? if true)
	 * @return \Peyote\Where
	 */
	public function orWhere($column, $op, $value, $isParam = true)
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
		return array('andWhere', 'orWhere');
	}

}
