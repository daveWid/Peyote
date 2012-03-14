<?php

namespace Peyote;

/**
 * An Expression is an unescaped SQL query to take advantage of additional
 * functionality of a given database.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Expression implements \Peyote\Builder
{
	/**
	 * @var string  The raw database expression 
	 */
	private $raw;

	/**
	 * Create a new Expression.
	 *
	 * @param string $value  The raw expression.
	 */
	public function __construct($value)
	{
		$this->raw = $value;
	}

	/**
	 * Magic method that is an alias for "compile".
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->compile();
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		return $this->raw;
	}

}
