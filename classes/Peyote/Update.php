<?php

namespace Peyote;

/**
 * A class that builds UPDATE statments.
 *
 * @link       http://dev.mysql.com/doc/refman/5.0/en/update.html
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Update extends \Peyote\Base
{
	/**
	 * @var array  The data to update
	 */
	private $data = array();

	/**
	 * @var \Peyote\Where  The where clause
	 */
	protected $where;

	/**
	 * @var \Peyote\OrderBy  The order by clause
	 */	
	protected $order_by;

	/**
	 * @var \Peyote\Limit  The limit clause
	 */
	protected $limit;

	/**
	 * Create a new Update instance.
	 *
	 * @param mixed $table   The table name OR array($table, $alias)
	 */
	public function __construct($table = null, \Peyote\Where $where = null)
	{
		if ($table !== null)
		{
			$this->table($table);
		}

		$this->where = ($where !== null) ? $where : new \Peyote\Where;
		$this->order_by = new \Peyote\OrderBy;
		$this->limit = new \Peyote\Limit;

		$map = array('where','order_by','limit');
		foreach ($map as $type)
		{
			$this->addToMap($type, $this->{$type}->getMethods());
		}
	}

	/**
	 * Sets the values as column => value pairs
	 *
	 * @param  array $data  An array of column => value pairs
	 * @return $this
	 */
	public function set(array $data)
	{
		$this->data = array_merge($this->data, $data);
		return $this;
	}

	/**
	 * Sets for the limit number.
	 *
	 * @param  int $num     The limit number
	 * @param  int $offset  The offset
	 * @return \Peyote\Update
	 */
	public function limit($num = null, $offset = null)
	{
		$this->limit->set_limit($num, $offset);
		return $this;
	}

	/**
	 * Add a where condition.
	 *
	 * @param  string $column  The column
	 * @param  string $op      The comparison operator
	 * @param  string $value   The value
	 * @return \Peyote\Update
	 */
	public function where($column, $op, $value)
	{
		$this->where->and_where($column, $op, $value);
		return $this;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		$sql = array("UPDATE");
		$sql[] = $this->table();
		$sql[] = "SET";

		$data = array();
		foreach ($this->data as $column => $value)
		{
			$data[] = "{$column} = {$this->quote($value)}";
		}

		$sql[] = implode(", ", $data);

		// Where?
		$where = $this->where->compile();
		if ($where !== "")
		{
			$sql[] = $where;
		}

		// Order?
		$order = $this->order_by->compile();
		if ($order !== "")
		{
			$sql[] = $order;
		}

		// Limit?
		$limit = $this->limit->compile();
		if ($limit !== "")
		{
			$sql[] = $limit;
		}

		return implode(" ", $sql);
	}

}
