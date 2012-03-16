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
	 * @link  http://kohanaframework.org/3.2/guide/database/query/builder#boolean-operators-and-nested-clauses 
	 */
	public function testBooleanAndNested()
	{
		$query = "SELECT *
FROM users
WHERE ( id IN (1, 2, 3, 5) AND ( last_login <= 1276020805 OR last_login IS NULL ) )
AND removed IS NULL";

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
		
		$this->assertEquals(str_replace(PHP_EOL, " ", $query), $select->compile());
	}

	/**
	 * @link http://www.mysqltutorial.org/mysql-join-subqueries.aspx
	 */
	public function testSelfJoins()
	{
		$query = "SELECT concat(e.firstname,',',e.lastname) AS employee, concat(m.firstname,',',m.lastname) AS manager
FROM employees AS m
INNER JOIN employees AS e
ON m.employeeNumber = e.reportsTo
ORDER BY employee";

		$select = new \Peyote\Select;
		$select->columns(
			array("concat(e.firstname,',',e.lastname)", "employee"),
			array("concat(m.firstname,',',m.lastname)", "manager")
		)->table(array("employees", "m"))
		->join(array("employees", "e"), "inner")
		->on("m.employeeNumber", "=", "e.reportsTo")
		->order_by("employee");

		$this->assertEquals(str_replace(PHP_EOL, " ", $query), $select->compile());
	}

	/**
	 * @link http://www.artfulsoftware.com/infotree/queries.php
	 */
	public function testCrossAggregates()
	{
		$query = "SELECT a1.bookid
FROM authorbook AS a1
INNER JOIN (
SELECT authid, COUNT(bookid)
FROM authorbook AS a2
GROUP BY authid
HAVING COUNT(bookid) > 1
) AS a3
ON a1.authid = a3.authid";

		$subquery = new \Peyote\Select;
		$subquery->columns("authid","COUNT(bookid)")
			->table(array("authorbook", "a2"))
			->group_by("authid")
			->having("COUNT(bookid)", ">", 1);

		$select = new \Peyote\Select;
		$select->columns("a1.bookid")
			->table(array("authorbook", "a1"))
			->join(array($subquery, "a3"), "inner")
			->on("a1.authid", "=", "a3.authid");

		$this->assertEquals(str_replace(PHP_EOL, " ", $query), $select->compile());
	}

	/**
	 * @link http://www.artfulsoftware.com/infotree/queries.php
	 */
	public function testNestedAggregation()
	{
		$query = "SELECT x.*, COUNT(b.passenger_id) AS bookings FROM (
SELECT DISTINCT p.passenger_id, p.name, d.destination
FROM passenger AS p
CROSS JOIN flight AS d
) AS x
LEFT JOIN flight AS d ON d.destination = x.destination
LEFT JOIN booking AS b ON b.passenger_id = x.passenger_id AND b.flight = d.flight
GROUP BY passenger_id, destination";

		$subquery = new \Peyote\Select;
		$subquery->distinct()
			->columns("p.passenger_id","p.name","d.destination")
			->table(array("passenger", "p"))
			->join(array("flight", "d"), "CROSS");

		$select = new \Peyote\Select;
		$select->columns("x.*", array("COUNT(b.passenger_id)", "bookings"))
			->table(array($subquery, "x"))
			->join(array("flight", "d"), "left")->on("d.destination", "=", "x.destination")
			->join(array("booking", "b"), "left")->on_and(array(
				array("b.passenger_id", "=", "x.passenger_id"),
				array("b.flight", '=', 'd.flight')
			))
			->group_by("passenger_id", "destination");

		$this->assertEquals(str_replace(PHP_EOL, " ", $query), $select->compile());
	}

	/**
	 * @link  http://dev.mysql.com/tech-resources/articles/subqueries_part_1.html
	 */
	public function testSubqueryHaving()
	{
		$query = "SELECT name, population, headofstate, top.nr
FROM Country,
(
	SELECT countrycode, COUNT(*) AS nr
	FROM CountryLanguage
	WHERE isofficial = 'T'
	GROUP BY countrycode
	HAVING nr = (
		SELECT MAX(summary.nr_official_languages)
		FROM
		(
			SELECT countrycode, COUNT(*) AS nr_official_languages
			FROM CountryLanguage
			WHERE isofficial = 'T'
			GROUP BY countrycode
		) AS summary
	)
) AS top
WHERE Country.code = top.countrycode";

		$sub3 = new \Peyote\Select;
		$sub3->columns("countrycode", array("COUNT(*)", 'nr_official_languages'))
			->table("CountryLanguage")
			->where("isofficial", '=', "T")
			->group_by("countrycode");

		$sub2 = new \Peyote\Select;
		$sub2->columns("MAX(summary.nr_official_languages)")
			->table(array($sub3, "summary"));

		$sub1 = new \Peyote\Select;
		$sub1->columns("countrycode", array("COUNT(*)", "nr"))
			->table("CountryLanguage")
			->where("isofficial", "=", "T")
			->group_by("countrycode")
			->having("nr", '=', $sub2);

		$select = new \Peyote\Select;
		$select->columns("name","population","headofstate","top.nr")
			->table("Country")
			->table(array($sub1, 'top'))
			->where("Country.code", "=", "top.countrycode");

		$query = preg_replace("/\s+/", " ", $query);
		$this->assertEquals($query, $select->compile());
	}

}