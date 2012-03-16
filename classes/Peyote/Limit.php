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
	 * Empty constructor to avoid php4 compatibility problems.
	 */
	public function __construct(){}

	/**
	 * @var int  The limit count
	 */
	private $limit = null;

	/**
	 * @var int  The select offset
	 */
	private $offset = null;

	/**
	 * Sets for the limit number.
	 *
	 * @param  int $num     The limit number
	 * @param  int $offset  The offset
	 * @return $this
	 */
	public function limit($num = null, $offset = null)
	{
		$this->limit = (int) $num;

		if ($offset !== null)
		{
			$this->offset($offset);
		}

		return $this;
	}

	/**
	 * Row offset (used only when selecting rows)
	 *
	 * @param  int $index  The row index to start the offset
	 * @return $this
	 */
	public function offset($index = null)
	{
		$this->offset = (int) $index;
		return $this;
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

		$sql = array("LIMIT");
		$sql[] = $this->limit;

		if ($this->offset !== null)
		{
			$sql[] = "OFFSET";
			$sql[] = $this->offset;
		}

		return implode(" ", $sql);
	}
}
