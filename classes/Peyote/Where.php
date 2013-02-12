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
	 * @param  string $column  The column name
	 * @param  string $op      The comparison operator
	 * @param  mixed  $value   The value to bind
	 * @return \Peyote\Where
	 */
	public function andWhere($column, $op, $value)
	{
		$this->clauses[] = array("AND", $column, $op, $value);
		return $this;
	}

	/**
	 * Builds an OR WHERE clause.
	 *
	 * @param  string $column  The column name
	 * @param  string $op      The comparison operator
	 * @param  mixed  $value   The value to bind
	 * @return \Peyote\Where
	 */
	public function orWhere($column, $op, $value)
	{
		$this->clauses[] = array("OR", $column, $op, $value);
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
