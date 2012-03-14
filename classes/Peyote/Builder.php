<?php

namespace Peyote;

/**
 * An interface for Peyote builder classes.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
interface Builder
{
	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile();
}
