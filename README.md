# MySQL Keeper

Find incorrect configurations or missed optimization opportunities in mysql databases.

### Implementation

```PHP
$mysqlKeeper = new MysqlKeeper($user, $password, $database);

$tablesResults = $mysqlKeeper->getAllTableResults();

$tableResult = $mysqlKeeper->getTableResult($tableName);
```

The `$tablesResults` array will look something like this with the first level keys being table names.

```PHP
[
	'users' => [
		'issues' => [
			[
				'type' => 'field',
				'name' => 'id',
				'description' => 'Set as auto_increment but is not set as primary'
			],
			[
				'type' => 'field',
				'name' => 'id',
				'description' => 'Set as auto_increment but is not set as primary'
			]
		],
		'optimizations' => [
			[
				'type' => 'field',
				'name' => 'id',
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
			'name' => 'id',
			'description' => 'Set as auto_increment but is not set as primary'
		],
		[
			'type' => 'field',
			'name' => 'id',
			'description' => 'Set as auto_increment but is not set as primary'
		]
	],
	'optimizations' => [
		[
			'type' => 'field',
			'name' => 'id',
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
