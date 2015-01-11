# MySQL Keeper

Find incorrect configurations or missed optimization opportunities in MySQL databases.

[![Travis](https://img.shields.io/travis/zortje/mysql-keeper.svg?style=flat)](https://travis-ci.org/zortje/mysql-keeper) [![Scrutinizer branch](https://img.shields.io/scrutinizer/coverage/g/zortje/mysql-keeper/master.svg?style=flat)](https://scrutinizer-ci.com/g/zortje/mysql-keeper/?branch=master) [![Scrutinizer](https://img.shields.io/scrutinizer/g/zortje/mysql-keeper.svg?style=flat)](https://scrutinizer-ci.com/g/zortje/mysql-keeper/?branch=master)

### Implementation

```PHP
$pdo = new PDO("mysql:host=127.0.0.1;dbname=myapp", 'root', 'password');

$database = new Database($pdo);
$databaseResult = $database->getResult();

$table = new Table('users', $pdo);
$tableResult = $table->getResult();
```

The `$databaseResult` array will look something like this with the first level keys being table names.

```PHP
[
	'users' => [
		'issues' => [
			[
				'type' => 'field',
				'field' => 'id',
				'description' => 'Set as auto_increment but has no primary key'
			],
			[
				'type' => 'field',
				'field' => 'id',
				'description' => 'Set as auto_increment but is not set as primary'
			]
		],
		'optimizations' => [
			[
				'type' => 'field',
				'field' => 'id',
				'description' => 'Field should be unsigned, as no field values are negative'
			]
		]
	]
];
```

The `$tableResult` array will look something like this.

```PHP
[
	'issues' => [
		[
			'type' => 'field',
			'field' => 'id',
			'description' => 'Set as auto_increment but is not set as primary'
		],
		[
			'type' => 'field',
			'field' => 'id',
			'description' => 'Set as auto_increment but is not set as primary'
		]
	],
	'optimizations' => [
		[
			'type' => 'field',
			'field' => 'id',
			'description' => 'Field should be unsigned, as no field values are negative'
		]
	]
];
```

### Checklist

* Issues
 * auto_increment should be primary
 * Redundant unique index (like on primary)
 * Inconsistent usage of character sets (table and fields)
 * Duplicate indices
* optimizations
 * Numeric fields should not be signed if no negative values are stored


### SQL cheat sheet

Get fields from table

```SQL
SHOW COLUMNS FROM `users`;
```


Get index information from table

```SQL
SHOW INDEX FROM `users`;
```
