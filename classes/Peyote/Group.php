<?php

namespace Peyote;

/**
 * The GROUP BY clause builder.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Group extends \Peyote\Sort implements \Peyote\Mixin
{
	/**
	 * Gets the type of sorting query we are running.
	 *
	 * @return string
	 */
	public function getType()
	{
		return "GROUP BY";
	}

	/**
	 * Groups a result with an optional direction.
	 *
	 * @param  string $column     The column name
	 * @param  string $direction  The direction to sort on
	 * @return \Peyote\Group
	 */
	public function groupBy($column, $direction = null)
	{
		$this->clauses[] = array($column, $direction);
		return $this;
	}

	/**
	 * Gets all of the methods that should be passed as "mixin" methods.
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return array('groupBy');
	}

}
