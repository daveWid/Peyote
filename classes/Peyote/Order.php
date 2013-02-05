<?php

namespace Peyote;

/**
 * The ORDER BY clause builder.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Order extends \Peyote\Sort implements \Peyote\Mixin
{
	/**
	 * Gets the type of sorting query we are running.
	 *
	 * @return string
	 */
	public function getType()
	{
		return "ORDER BY";
	}

	/**
	 * Orders a result with an optional direction.
	 *
	 * @param  string $column     The column name
	 * @param  string $direction  The direction to sort on
	 * @return \Peyote\Order
	 */
	public function orderBy($column, $direction = null)
	{
		$this->clauses[] = array($column, $direction);
		return $this;
	}

	/**
	 * Gets all of the methods that should be passed as "trait" methods.
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return array('orderBy');
	}

}
