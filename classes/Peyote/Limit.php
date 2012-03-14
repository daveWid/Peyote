<?php

namespace Peyote;

/**
 * A class for LIMIT statements.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Limit implements \Peyote\Builder
{
	/**
	 * @var int  The limit count
	 */
	private $limit = null;

	/**
	 * Getter/Setter for the limit number.
	 *
	 * @param  int $num  The limit number [set]
	 * @return int       The limit number [get] OR $this [set]
	 */
	public function limit($num = null)
	{
		if ($num === null)
		{
			return $this->limit;
		}

		$this->limit = (int) $num;
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		return ($this->limit === null) ? "" : "LIMIT {$this->limit}";
	}
}
