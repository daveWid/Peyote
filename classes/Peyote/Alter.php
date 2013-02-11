<?php

namespace Peyote;

/**
 * A class to allow table altering.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Alter implements Builder
{
	/**
	 * @var string
	 */
	private $table = null;

	/**
	 * @var array
	 */
	private $conditions = array();

	/**
	 * @param string $table  The table name
	 */
	public function __construct($table = null)
	{
		$this->setTable($table);
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
	 * @return \Peyote\Alter
	 */
	public function setTable($table)
	{
		$this->table = $table;
		return $this;
	}

	/**
	 * @return array  A list of alter conditions
	 */
	public function getConditions()
	{
		return $this->conditions;
	}

	/**
	 * @param string $column     The column name
	 * @param string $definition The column definition
	 * @return \Peyote\Alter     $this
	 */
	public function addColumn($column, $definition)
	{
		$this->conditions[] = array("ADD", $column, $definition);
		return $this;
	}

	/**
	 * @param  string $column The columns name
	 * @return \Peyote\Alter
	 */
	public function addPrimaryKey($column)
	{
		return $this->addIndex($column, "PRIMARY KEY");
	}

	/**
	 * 
	 * @param  string $column The column name to add as a key
	 * @param  string $type   The type of key to add
	 * @return \Peyote\Alter  $this
	 */
	public function addIndex($column, $type = "INDEX")
	{
		$this->conditions[] = array("ADD", $type, "({$column})");
		return $this;
	}

	/**
	 * @param  string $column The column name
	 * @return \Peyote\Alter  $this
	 */
	public function dropColumn($column)
	{
		$this->conditions[] = array("DROP", $column, "");
		return $this;
	}

	/**
	 * @return \Peyote\Alter  $this
	 */
	public function dropPrimaryKey()
	{
		$this->conditions[] = array("DROP", "PRIMARY", "KEY");
	}

	/**
	 * @param  string $index  The index
	 * @return \Peyote\Alter  $this
	 */
	public function dropIndex($index, $type = "INDEX")
	{
		$this->conditions[] = array("DROP", $type, $index);
		return $this;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Peyote\Exception
	 */
	public function compile()
	{
		$conditions = $this->getConditions();

		if (empty($conditions))
		{
			throw new \Peyote\Exception("\Peyote\Alter is an empty statement");
		}

		$query = array("ALTER", "TABLE", $this->getTable());

		foreach ($conditions as $condition)
		{
			$query[] = rtrim(join(' ', $condition));
		}

		return join(' ', $query);
	}

}
