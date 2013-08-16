<?php

namespace Peyote;

use \Peyote\Facade as Peyote;

/**
 * A very simple wrapper for executing PDO based queries.
 *
 * @package    Peyote
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class PDO
{
	/**
	 * @var \PDO  The PDO instance used to query the database
	 */
	protected $pdo;

	/**
	 * @param PDO $pdo  The PDO instance used to query the database
	 */
	public function __construct(\PDO $pdo)
	{
		$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);

		$this->pdo = $pdo;
	}

	/**
	 * @param  string $table The table to insert into
	 * @param  array  $data  The data to add
	 * @return int           The id of the last inserted row
	 */
	public function insert($table, array $data)
	{
		$query = Peyote::insert($table)
			->columns(\array_keys($data))
			->values(\array_values($data));

		$this->runQuery($query);
		return (int) $this->pdo->lastInsertId();
	}

	/**
	 * Run a Peyote based query.
	 *
	 * @param  \Peyote\Query $query  A Peyote Query
	 * @return \PDOStatement
	 */
	public function runQuery(\Peyote\Query $query)
	{
		return $this->run($query->compile(), $query->getParams());
	}

	/**
	 * Run a MySQL query.
	 *
	 * @param  string $query   The query to run
	 * @param  array  $params  Any query params to add
	 * @return \PDOStatement
	 */
	public function run($query, array $params = array())
	{
		$statement = $this->pdo->prepare($query);
		$statement->execute($params);

		return $statement;
	}

}
