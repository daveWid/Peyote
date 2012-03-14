<?php

namespace Peyote;

/**
 * A class that builds delete statments.
 *
 * @link       http://dev.mysql.com/doc/refman/5.0/en/delete.html
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class Delete extends \Peyote\Base
{
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
	 * Create a new Delete instance.
	 *
	 * @param mixed         $table   The table name OR array($table, $alias)
	 * @param \Peyote\Where $where
	 */
	public function __construct($table = "", \Peyote\Where $where = null)
	{
		$this->table($table);
		$this->where = ($where !== null) ? $where : new \Peyote\Where;
		$this->order_by = new \Peyote\OrderBy;
		$this->limit = new \Peyote\Limit;
	}

	/**
	 * Compiles the query into raw SQL
	 *
	 * @return  string
	 */
	public function compile()
	{
		$sql = array("DELETE FROM");
		$sql[] = $this->table();

		// Where?
		$where = $this->where->compile();
		if ($where !== "")
		{
			$sql[] = $where;
		}

		// Order
		$order = $this->order_by->compile();
		if ($order !== "")
		{
			$sql[] = $order;
		}

		// Limit
		$limit = $this->limit->compile();
		if ($limit !== "")
		{
			$sql[] = $limit;
		}

		return implode(" ", $sql);
	}

	/**
	 * Get the class properties to use as "traits".
	 *
	 * @return array
	 */
	protected function traits()
	{
		return array("where","order_by","limit");
	}

}
