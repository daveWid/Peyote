<?php
/**
 * A collection of random queries...
 */
class RandomTest extends PHPUnit_Framework_TestCase
{

	public function testOrLikes()
	{
		$sql = "SELECT DISTINCT a, b, c FROM table WHERE d LIKE e OR f LIKE e OR g LIKE e ORDER BY h";

		$query = new \Peyote\Select('table');
		$query->distinct()
			->columns("a", "b", "c")
			->where('d', 'LIKE', 'e', false)
			->orWhere('f', 'LIKE', 'e', false)
			->orWhere('g', 'LIKE', 'e', false)
			->orderBy('h');

		$this->assertSame($sql, $query->compile());
	}

}