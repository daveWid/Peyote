<?php

namespace Peyote;

/**
 * The LIMIT # clause builder.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Limit implements \Peyote\Builder, \Peyote\Mixin
{
	/**
	 * @var int  The limit number
	 */
	private $limit = null;
	
	/**
	 * @var int  The offset amount
	 */
	private $offset = null;

	/**
	 * Sets the limit.
	 *
	 * @param int $num  The number to limit the queries to
	 * @param int $offset The offset of which row to start the results with
	 */
	public function setLimit($num, $offset = 0)
	{
		$this->limit = (int) $num;
		$this->offset = (int) $offset;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		if ($this->limit === null)
		{
			return "";
		}
		
		return "LIMIT {$this->offset}, {$this->limit}";
	}

	/**
	 * Gets all of the methods that should be passed as "mixin" methods.
	 *
	 * @return array
	 */
	public function getMethods()
	{
		return array();
	}

}
