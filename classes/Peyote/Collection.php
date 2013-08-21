<?php

namespace Peyote;

/**
 * A collection of data. Really useful for result sets.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Collection implements \ArrayAccess, \IteratorAggregate, \Countable
{
	/**
	 * @var array  The list of models in the collection
	 */
	protected $models = array();

	/**
	 * @param array $models  An array of models to add to the collection
	 */
	public function __construct(array $models = array())
	{
		$this->add($models);
	}

	/**
	 * @param  mixed $item  Adds one model to the collection
	 * @return \Peyote\Collection
	 */
	public function addOne($item)
	{
		$this->models[] = $item;
		return $this;
	}

	/**
	 * @param  array $items   An array of models to add to the collection
	 * @return \Peyote\Model
	 */
	public function add(array $models)
	{
		$this->models = \array_merge($this->models, $models);
		return $this;
	}

	/**
	 * @param  int $index  The index of the item to grab. 0 indexed
	 * @return mixed
	 */
	public function at($index)
	{
		return $this->offsetGet($index);
	}

	/**
	 * @param  array  $items The items to replace
	 * @return \Peyote\Collection
	 */
	public function replace(array $models)
	{
		$this->reset();
		$this->add($models);
	}

	/**
	 * @return \Peyote\Collection
	 */
	public function reset()
	{
		$this->models = array();
		return $this;
	}

	/**
	 * @return array  Returns the collection as an array.
	 */
	public function toArray()
	{
		return \array_map(function($item){
			if ($item instanceof \Peyote\Model)
			{
				$item = $item->toArray();
			}

			return $item;
		}, $this->models);
	}

	/**
	 * This library is currently written for 5.3
	 *
	 * This is a holder class for when 5.4 is supported and the JsonSerializable
	 * interface can be implemented.
	 *
	 * @link   http://php.net/manual/en/class.jsonserializable.php
	 * @return mixed   A json encoded string
	 */
	public function jsonSerialize()
	{
		return $this->toJSON();
	}

	/**
	 * @return string  A json representation of the collection
	 */
	public function toJSON()
	{
		return \json_encode($this->toArray());
	}

/** ==============
    ArrayAccess
	============== */
	public function offsetExists($offset)
	{
		return isset($this->models[$offset]);
	}

	public function offsetGet($offset)
	{
		return $this->models[$offset];
	}

	public function offsetSet($offset, $value)
	{
		$this->models[$offset] = $value;
	}

	public function offsetUnset($offset)
	{
		unset($this->models[$offset]);
	}

/** ====================
    IteratorAggregate
	==================== **/
	public function getIterator()
	{
		return new \ArrayIterator($this->models);
	}

/** =================
    Countable
	================= **/
	public function count()
	{
		return count($this->models);
	}

}
