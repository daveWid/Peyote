# Peyote

A MySQL query builder engine for PDO which requires PHP 5.3.

## Installation

The easiest way to install Peyote is by adding this line to your
[composer.json](http://getcomposer.org/) file.

``` json
"require":{
	"davewid/peyote": "0.5.*"
},
```

Optionally you can [download](https://github.com/daveWid/Peyote/downloads)
a package from github.

## Standards

Peyote follows both the [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md)
and [PSR-1](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md)
standards.

There isn't an autoloader included with the library though so you will need to
set that up yourself. If you use Composer to install the dependency (_highly recommended_)
then you won't have to worry about anything as Composer will take care of it all.

## Example

I'll start out with a full example on how to use the library and break it down
as we go along.

``` php
<?php

// Create a PDO instance
$pdo = new PDO($dsn, $user, $password);

// Create a SELECT query
$query = new \Peyote\Select('user');
$query->where('user_id', '=', 1);

// Build the PDOStatement
$statement = $pdo->prepare($query->compile());

// Run the query
$statement->execute($query->getParams());

// Fetch results
$results = $statement->fetchAll();
```

#### Why the `getParams()` call?

Keeping your queries safe from SQL injection is out of the scope of this library
so Peyote uses ? placeholders instead and keeps track of all the data you enter.

If you would echo out $query->compile() you would see this.

``` sql
SELECT * FROM user WHERE user_id = ?
```

At this point `getParams()` will return an array holding the values you passed in,
(_in this case, 1_).

PDO will handle the placeholder replacement during `execute()` keeping
you a lot safer from SQL injection.

## SELECT

``` php
<?php

$query = new \Peyote\Select('user');
$query->where('user_id', '=', 1);

echo $query->compile();
// output: SELECT * FROM user WHERE user_id = ?
```

## INSERT

``` php
<?php

$data = array(
	'email' => "testing@foo.com",
	'password' => "youllneverguess"
);

$query = new \Peyote\Insert('user');
$query->columns(array_keys($data))->values(array_values($data));

echo $query->compile();
// output: INSERT INTO user (email, password) VALUES (?, ?)
```

## UPDATE

``` php
<?php

$data = array(
	'password' => "iguesssomebodyguessed"
);

$query = new \Peyote\Update('user');
$query->set($data)->where('user_id', '=', 1);

echo $query->compile();
// output: UPDATE user SET password = ? WHERE user_id = ?
```

## DELETE

``` php
<?php

$query = new \Peyote\Delete('user');
$query->where('user_id', '=', 1);

echo $query->compile();
// output: DELETE FROM user WHERE user_id = ?
```

----

Developed by [Dave Widmer](http://www.davewidmer.net)
