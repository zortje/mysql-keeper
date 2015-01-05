# MySQL Keeper

Find incorrect configurations or missed optimization opportunities in mysql databases.

```PHP
$mysqlKeeper = new MysqlKeeper($user, $password, $database);

$tableResults = $mysqlKeeper->getTablesResults();
```

Table results array will look something like this with the first level keys being table names.

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
				'description' => 'Field should be unsigned, as no field values are below zero'
			]
		]
	]
];
```
