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
	 * Sets for the limit number.
	 *
	 * @param  int $num  The limit number
	 * @return $this
	 */
	public function limit($num = null)
	{
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
