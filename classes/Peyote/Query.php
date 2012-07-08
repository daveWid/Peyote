<?php

namespace Peyote;

/**
 * The base Query class for SELECT, INSERT, UPDATE and DELETE statement.
 *
 * These four classes will share some functionality, so let us share!
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Query implements \Peyote\Builder
{
	/**
	 * @var array  Bound parameters
	 */
	protected $params = array();

	/**
	 * @var array  A map of passthru methods
	 */
	private $method_map = array();

	/**
	 * @var array  A list of traits that the query can passthru
	 */
	protected $traits = array();

	/**
	 * @var string  The name of the table
	 */
	protected $table = null;

	/**
	 * Creates the method map. Make sure you call parent::__construct()
	 * after all of your main passthru objects are created.
	 *
	 * @param string $table       The name of the table
	 */
	public function __construct($table = null)
	{
		$this->table = $table;

		foreach ($this->traits as $trait)
		{
			$this->addMethods($trait, $this->{$trait}->getMethods());
		}
	}

	/**
	 * Sets the name of the table.
	 *
	 * @param  string $name  The table name
	 * @return \Peyote\Query
	 */
	public function table($name)
	{
		$this->table = $name;
		return $this;
	}

	/**
	 * Adds "trait" methods to this class.
	 *
	 * @param  string $trait   The name of the trait
	 * @param  array $methods  A list of methods to add
	 * @return \Petoyte\Query
	 */
	public function addMethods($trait, array $methods)
	{
		foreach ($methods as $method)
		{
			$this->method_map[$method] = $trait;
		}

		return $this;
	}

	/**
	 * Gets all of the bound parameters for this query.
	 *
	 * @return array
	 */
	public function getParams()
	{
		return $this->params;
	}

	/**
	 * The only bit of magic in this codebase. This is necessary so we can add
	 * trait like functionality in 5.3
	 *
	 * @throws \Peyote\Exception
	 *
	 * @param  string $name   The name of the method to call
	 * @param  array  $param  The passed in parameters
	 * @return \Peyote\Query
	 */
	public function __call($name, $params)
	{
		if (array_key_exists($name, $this->method_map))
		{
			$property = $this->method_map[$name];

			call_user_func_array(array($this->{$property}, $name), $params);
			return $this;
		}
		else
		{
			throw new \Peyote\Exception("Call to undefined method ".get_called_class()."::{$name}()");
		}
	}

}
