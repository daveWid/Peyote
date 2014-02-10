<?php

namespace Peyote;

/**
 * Building a SELECT query.
 *
 * The join(), select(), and limit() functions should just be passthrus, but I
 * was getting an error when naming a function the same as the class (php4 FTW!)
 * so I had to just move the methods here and call back. Lame I know, but the
 * only other choice was to go back down to 5.2 and name everything with
 * underscores, and I didn't want to do that, so here we are...
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Select extends \Peyote\Query
{
	/**
	 * @var \Peyote\Join  The join object
	 */
	protected $join = null;

	/**
	 * @var \Peyote\Where  The where object
	 */
	protected $where = null;

	/**
	 * @var \Peyote\Group  The group object
	 */
	protected $group_by = null;

	/**
	 * @var \Peyote\Order  The order object
	 */
	protected $order_by = null;

	/**
	 * @var \Peyote\Limit  The limit object
	 */
	protected $limit = null;

	/**
	 * @var array  A list of mixins that the query can passthru
	 */
	protected $mixins = array('join', 'where', 'group_by', 'order_by', 'limit', 'having');

	/**
	 * @var int  The number to offset by.
	 */
	private $offset_num = null;

	/**
	 * @var boolean  Run a distinct query?
	 */
	private $is_distinct = false;

	/**
	 * @var array  A list of columns to search for
	 */
	private $columns = array();

	/**
	 * Optionally sets the table name and initializes the internal class
	 * properties.
	 *
	 * @param string $table  The name of the table
	 */
	public function __construct($table = null)
	{
		$this->join = new \Peyote\Join;
		$this->where = new \Peyote\Where;
		$this->group_by = new \Peyote\Group;
		$this->order_by = new \Peyote\Order;
		$this->limit = new \Peyote\Limit;
		$this->having = new \Peyote\Having;

		parent::__construct($table);
	}

	/**
	 * Run a SELECT DISTINCT query.
	 *
	 * @return \Peyote\Select
	 */
	public function distinct()
	{
		$this->is_distinct = true;
		return $this;
	}

	/**
	 * Specify the columns to select.
	 *
	 * @param  string ...  Any number of string column names
	 * @return \Peyote\Select
	 */
	public function columns()
	{
		return $this->columnsArray(func_get_args());
	}

	/**
	 * Specify the columns to select as an array.
	 *
	 * @param  array $columns  The columns to select
	 * @return \Peyote\Select
	 */
	public function columnsArray(array $columns)
	{
		$this->columns = $columns;
		return $this;
	}

	/**
	 * Add a join.
	 *
	 * @param  string $table  The table name
	 * @param  string $type   The type of join
	 * @return \Peyote\Select
	 */
	public function join($table, $type = null)
	{
		$this->join->addJoin($table, $type);
		return $this;
	}

	/**
	 * Adds a WHERE clause.
	 *
	 * @param  string  $column  The column name
	 * @param  string  $op      The comparison operator
	 * @param  mixed   $value   The value to bind
	 * @param  boolean $isParam Is this a parameter (replace value with ? if true)
	 * @return \Peyote\Select
	 */
	public function where($column, $op, $value, $isParam = true)
	{	
		$this->where->andWhere($column, $op, $value, $isParam);
		return $this;
	}
	
	/**
	 * Adds a HAVING clause.
	 *
	 * @param  string  $column  The column name
	 * @param  string  $op      The comparison operator
	 * @param  mixed   $value   The value to bind
	 * @param  boolean $isParam Is this a parameter (replace value with ? if true)
	 * @return \Peyote\Select
	 */
	public function having($column, $op, $value, $isParam = true)
	{	
		$this->having->andHaving($column, $op, $value, $isParam);
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
	 * The offset number.
	 *
	 * @param  int $num The number of rows to offset by
	 * @return \Peyote\Select
	 */
	public function offset($num)
	{
		$this->offset_num = (int) $num;
		return $this;
	}

	/**
	 * Gets all of the bound parameters for this query.
	 *
	 * @return array
	 */
	public function getParams()
	{
		return array_merge($this->where->getParams(), $this->having->getParams());
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		$sql = array("SELECT");

		if ($this->is_distinct)
		{
			$sql[] = "DISTINCT";
		}

		if (empty($this->columns))
		{
			$sql[] = "*";
		}
		else
		{
			$sql[] = join(', ', $this->columns);
		}

		$sql[] = "FROM";
		$sql[] = $this->table;

		$sql = \array_merge($sql, $this->compileMixins());

		if ($this->offset_num !== null)
		{
			$sql[] = "OFFSET {$this->offset_num}";
		}

		return join(' ', $sql);
	}

}
