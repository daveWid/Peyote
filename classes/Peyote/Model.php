<?php

namespace Peyote;

/**
 * A model class will let you easily track and validate data.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Model implements \ArrayAccess, \IteratorAggregate
{
	/**
	 * @var string  The property that holds is the "id"
	 */
	public $idProperty = 'id';

	/**
	 * @var array  The internal data store
	 */
	protected $data = array();

	/**
	 * @var array  A listing of all of the modified data
	 */
	protected $modified_data = array();

	/**
	 * @param array $data  Any initial data
	 */
	public function __construct(array $data = array())
	{
		$this->replace($data);
	}

	/**
	 * @return boolean
	 */
	public function isNew()
	{
		return $this->get($this->idProperty) === null;
	}

	/**
	 * Checks to see if a key exists.
	 *
	 * @param  string $key  The key to check for
	 * @return boolean
	 */
	public function has($key)
	{
		return $this->offsetExists($key);
	}

	/**
	 * @param  string $name    The property name to get
	 * @param  mixed  $default The default value if the property isn't found
	 * @return mixed           The property value or default
	 */
	public function get($name, $default = null)
	{
		if ( ! $this->offsetExists($name))
		{
			return $default;
		}

		return $this->offsetGet($name);
	}

	/**
	 * @return array  Get all of the data that has been modified
	 */
	public function getModifiedData()
	{
		return $this->modified_data;
	}

	/**
	 * @param  mixed  $prop      The property name as string or an array of key => value pairs
	 * @param  mixed  $value     The property value
	 * @return \Peyote\Model     $this
	 */
	public function set($prop, $value = null)
	{
		if ( ! \is_array($prop))
		{
			$prop = array($prop => $value);
		}

		foreach ($prop as $key => $value)
		{
			$this->offsetSet($key, $value);
		}

		return $this;
	}

	/**
	 * @param  string $key    The property name
	 * @return \Peyote\Model  $this
	 */
	public function delete($key)
	{
		$this->offsetUnset($key);
		return $this;
	}

	/**
	 * @return array  An array of errors (empty array for valid data)
	 */
	public function validate()
	{
		return array();
	}

	/**
	 * @param  array  $data   The new data array
	 * @return \Peyote\Model  $this
	 */
	public function replace(array $data)
	{
		$this->clear();

		$this->set($data);
		return $this->reset();
	}

	/**
	 * Clears out the data.
	 *
	 * @return \Peyote\Model  $this
	 */
	public function clear()
	{
		$this->data = array();
		return $this;
	}

	/**
	 * @return \Peyote\Model
	 */
	public function reset()
	{
		$this->modified_data = array();
		return $this;
	}

	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->data;
	}

	/**
	 * @link   http://www.php.net/manual/en/function.json-encode.php
	 *
	 * @param  int $options  The JSON options constant
	 * @return string        A json encoded string
	 */
	public function toJSON($options = 0)
	{
		return json_encode($this->data, $options);
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
		return $this->toJSON($this->data);
	}

/** ====================
	Object Access
	==================== **/

    /**
     * @param string $propery  The property to access
     */
	public function __get($property)
	{
		return $this->offsetGet($property);
	}

	/**
	 * @param string $property The property to set
	 * @param mixed  $value    The value to set
	 */
	public function __set($property, $value)
	{
		$this->offsetSet($property, $value);
	}

	/**
	 * @param  string  $prop The property name to check for
	 * @return boolean
	 */
	public function __isset($prop)
	{
		return $this->offsetExists($prop);
	}

/** ====================
	ArrayAccess
	==================== **/

	/**
	 * @param  mixed  $offset  The offest name
	 * @return boolean
	 */
	public function offsetExists($offset)
	{
		return \array_key_exists($offset, $this->data);
	}

	/**
	 * @param  mixed  $offset The offset to find
	 * @return mixed          The offset value
	 */
	public function offsetGet($offset)
	{
		return $this->data[$offset];
	}

	/**
	 * @param  mixed  $offset The property name
	 * @param  mixed  $value  The property value
	 */
	public function offsetSet($offset, $value)
	{
		if ( ! $this->offsetExists($offset) OR $this->offsetGet($offset) !== $value)
		{
			$this->modified_data[$offset] = $value;
			$this->data[$offset] = $value;
		}
	}

	/**
	 * @param  mixed  $offset  The property name
	 */
	public function offsetUnset($offset)
	{
		unset($this->data[$offset]);
	}

/** ====================
	IteratorAggregate
	==================== */

	/**
	 * @return \Traversable
	 */
	public function getIterator()
	{
		return new \ArrayIterator($this->data);
	}

}
