<?php

namespace Peyote;

/**
 * Building a DELETE query.
 *
 * See Select for the full rant, but the tl;dr is that php 4 is messing up my
 * classes, so I have to actually declare where() and limit() in this class
 * instead of just using the cool passthru functionality.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Delete extends \Peyote\Query
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
	 * @var array  A list of traits that the query can passthru
	 */
	protected $traits = array('where', 'order_by', 'limit');

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
		 *
		 * Since where is the only clause that would have params, let's just
		 * fetch those
		 */
		return $this->where->getParams();
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		$sql = array("DELETE FROM");
		$sql[] = $this->table;

		foreach ($this->traits as $trait)
		{
			$compiled = $this->{$trait}->compile();
			if ($compiled !== "")
			{
				$sql[] = $compiled;
			}
		}

		return join(' ', $sql);
	}

}
