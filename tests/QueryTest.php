<?php

/**
 * This testing setup is where you can test out more complex queries to make sure
 * that they build correctly.
 *
 * @package    Peyote
 * @category   Tests
 * @author     Dave Widmer <dave@davewidmer.net>
 */
class QueryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * This test was taken from the Kohana Query Builder docs.
	 *
	 * @link  http://kohanaframework.org/3.2/guide/database/query/builder#boolean-operators-and-nested-clauses 
	 */
	public function testBooleanAndNested()
	{
		$select = new \Peyote\Select;
		$select->table('users')
			->where_open()
				->or_where('id', 'IN', array(1, 2, 3, 5))
				->and_where_open()
					->where('last_login', '<=', 1276020805)
					->or_where('last_login', 'IS', NULL)
				->where_close()
			->where_close()
			->and_where('removed','IS', NULL);
		
		$query = "SELECT * FROM users WHERE ( id IN (1, 2, 3, 5) OR ( last_login <= 1276020805 OR last_login IS NULL ) ) AND removed IS NULL";

		$this->assertEquals($query, $select->compile());
	}

}