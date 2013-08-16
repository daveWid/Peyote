<?php

namespace Peyote;

/**
 * A Facade class to make creating queries easier.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Facade
{
	/**
	 * Facade for creating a new \Peyote\Select instance.
	 *
	 * @param  string $name The table name
	 * @return \Peyote\Select
	 */
	public static function select($name = "")
	{
		return new Select($name);
	}

	/**
	 * Facade for creating a new \Peyote\Insert instance.
	 *
	 * @param  string $name The table name
	 * @return \Peyote\Insert
	 */
	public static function insert($name = "")
	{
		return new Insert($name);
	}

	/**
	 * Facade for creating a new \Peyote\Update instance.
	 *
	 * @param  string $name The table name
	 * @return \Peyote\Update
	 */
	public static function update($name = "")
	{
		return new Update($name);
	}

	/**
	 * Facade for creating a new \Peyote\Delete instance.
	 *
	 * @param  string $name The table name
	 * @return \Peyote\Delete
	 */
	public static function delete($name = "")
	{
		return new Delete($name);
	}

}
