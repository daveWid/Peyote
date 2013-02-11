<?php

namespace Peyote;

/**
 * The DROP table class
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Drop implements Builder
{
	/**
	 * @var string
	 */
	private $table = null;

	/**
	 * @var boolean
	 */
	private $if_exists = false;

	/**
	 * @param string $table The table name
	 */
	public function __construct($table = null, $if_exists = false)
	{
		$this->setTable($table);
		$this->setIfExists($if_exists);
	}

	/**
	 * @return string
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @param string $table The table name to drop.
	 * @return \Peyote\Drop
	 */
	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getIfExists()
	{
		return $this->if_exists;
	}

	/**
	 * @param  boolean $exists Add the IF EXISTS clause?
	 * @return \Peyote\Drop
	 */
	public function setIfExists($exists)
	{
		$this->if_exists = (boolean) $exists;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function compile()
	{
		$query = array("DROP", $this->getTable());

		if ($this->getIfExists() === true)
		{
			$query[] = "IF EXISTS";
		}

		return join(' ', $query);
	}

}
