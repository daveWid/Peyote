<?php

namespace Peyote;

/**
 * Building a UPDATE query.
 *
 * See Select for the full rant, but the tl;dr is that php 4 is messing up my
 * classes, so I have to actually declare where() and limit() in this class
 * instead of just using the cool passthru functionality.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Update extends \Peyote\Query
{
	/**
	 * @var \Peyote\Where  The where object
	 */
	protected $where = null;

	/**
	 * @var \Peyote\Order  The order object
	 */
	protected $order_by = null;

	/**
	 * @var \Peyote\Limit  The limit object
	 */
	protected $limit = null;

	/**
	 * @var array  The key/value pair data
	 */
	private $data = array();

	/**
	 * @var array  A list of mixins that the query can passthru
	 */
	protected $mixins = array('where', 'order_by', 'limit');

	/**
	 * Optionally sets the table name and initializes the internal class
	 * properties.
	 *
	 * @param string $table  The name of the table
	 */
	public function __construct($table = null)
	{
		$this->where = new \Peyote\Where;
		$this->order_by = new \Peyote\Order;
		$this->limit = new \Peyote\Limit;

		parent::__construct($table);
	}

	/**
	 * Set key/value pairs for the update.
	 *
	 * @param  array $data  The data to set
	 * @return \Peyote\Update
	 */
	public function set(array $data)
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * Adds a WHERE clause.
	 *
	 * @param  string $column  The column name
	 * @param  string $op      The comparison operator
	 * @param  mixed  $value   The value to bind
	 * @return \Peyote\Delete
	 */
	public function where($column, $op, $value)
	{	
		$this->where->andWhere($column, $op, $value);
		return $this;
	}

	/**
	 * Sets the limit.
	 *
	 * @param  int $num  The number to limit the queries to
	 * @return \Peyote\Select
	 */
	public function limit($num)
	{
		$this->limit->setLimit($num);
		return $this;
	}

	/**
	 * Gets all of the bound parameters for this query.
	 *
	 * @return array
	 */
	public function getParams()
	{
		/**
		 * Instead of writing some crazy magic foo to pull the params
		 * automatically, I'm just overridding this function to get the
		 * correct params.
		 */
		return array_merge($this->params, $this->where->getParams());
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		$sql = array("UPDATE");
		$sql[] = $this->table;
		$sql[] = "SET";

		$set = array();
		foreach ($this->data as $key => $value)
		{
			$set[] = "{$key} = ?";
			$this->params[] = $value;
		}

		$sql[] = join(', ', $set);

		$sql = \array_merge($sql, $this->compileMixins());

		return join(' ', $sql);
	}

}
