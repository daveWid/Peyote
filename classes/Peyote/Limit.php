<?php

namespace Peyote;

/**
 * The LIMIT # clause builder.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Limit implements \Peyote\Builder
{
	/**
	 * @var int  The limit number
	 */
	private $limit = null;

	/**
	 * Sets the limit.
	 *
	 * @param int $num  The number to limit the queries to
	 */
	public function setLimit($num)
	{
		$this->limit = (int) $num;
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

		return "LIMIT {$this->limit}";
	}

}
