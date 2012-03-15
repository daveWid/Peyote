<?php

namespace Peyote;

/**
 * An interface for Peyote builder classes.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Base implements \Peyote\Builder
{
	/**
	 * @var mixed  The name of the database table OR array($table, $alias)
	 */
	private $table;

	/**
	 * Quotes/Escapes a value for the database.
	 *
	 * @param  string $value  The value to quote
	 * @param  string $escape Should we escape the output too?
	 * @return string         The quoted/escaped variable
	 */
	public function quote($value, $escape = true)
	{
		if (is_string($value))
		{
			if ($escape === true)
			{
				$value = $this->escape($value);
			}

			$value = "'{$value}'";
		}
		elseif (is_null($value))
		{
			$value = "NULL";
		}
		elseif (is_array($value))
		{
			$value = "(".implode(", ", array_map(array($this, "quote"), $value, array($escape))).")";
		}

		// If it is a number, then we don't need to do anything...

		return $value;
	}

	/**
	 * Escapes a value to be inserted into the database.
	 *
	 * @param  mixed  $value  The value to escape
	 * @return mixed          The escaped value
	 */
	public function escape($value)
	{
		$search = array('\\', "\0", "\n", "\r", "'", '"', "\x1a");
		$replace = array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z');

		return str_replace($search, $replace, $value);
	}

	/**
	 * Getter/Setter for the name of the database table.
	 *
	 * @param  mixed $table  The name of the table OR array($table, $alias) [set]
	 * @return mixed         The name of the table [get] OR $this
	 */
	public function table($table = null)
	{
		if ($table === null)
		{
			return $this->table;
		}

		if (is_array($table))
		{
			list ($table, $alias) = $table;
			$this->table = ($table instanceof \Peyote\Select) ?
				"( {$table} ) AS {$alias}" :
				"{$table} AS {$alias}";
		}
		else
		{
			$this->table = ($table instanceof \Peyote\Select) ? "( {$table} )" : $table;
		}

		return $this;
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
	 * This method is called when a property is not found on the builder object.
	 * It will loop through all of the properties returned with $this->traits()
	 * and see if that function has the method. If a method is found, it will
	 * be called and $this returned. If the method is not found and \Execption
	 * will be thrown.
	 *
	 * @throws \Peyote\Exeption
	 *
	 * @param  string $method  The name of the method
	 * @param  array  $params  The passed in parameters
	 * @return $this 
	 */
	public function __call($method, $params)
	{
		foreach ($this->traits() as $prop)
		{
			if ($this->{$prop} !== null AND method_exists($this->{$prop}, $method))
			{
				call_user_func_array(array($this->{$prop}, $method), $params);
				return $this;
			}
		}

		throw new \Peyote\Exception("{$method} does not exist in ".get_called_class());
	}

	/**
	 * Adding in basic trait support for php 5.3.
	 *
	 * This function will return all of the class properties that should
	 * be looped through when __call is run. The properties will be checked
	 * in order so put the properties you want to have priority first.
	 *
	 * @return array 
	 */
	protected function traits()
	{
		return array();
	}

}
