# MySQL Keeper

Find incorrect configurations or missed optimization opportunities in MySQL databases.

[![Packagist](https://img.shields.io/packagist/v/zortje/mysql-keeper.svg?style=flat)](https://packagist.org/packages/zortje/mysql-keeper) [![Travis](https://img.shields.io/travis/zortje/mysql-keeper.svg?style=flat)](https://travis-ci.org/zortje/mysql-keeper) [![Scrutinizer branch](https://img.shields.io/scrutinizer/coverage/g/zortje/mysql-keeper/master.svg?style=flat)](https://scrutinizer-ci.com/g/zortje/mysql-keeper/?branch=master) 

[![Scrutinizer](https://img.shields.io/scrutinizer/g/zortje/mysql-keeper.svg?style=flat)](https://scrutinizer-ci.com/g/zortje/mysql-keeper/?branch=master) [![Code Climate](https://img.shields.io/codeclimate/github/zortje/mysql-keeper.svg)](https://codeclimate.com/github/zortje/mysql-keeper) [![Codacy](https://img.shields.io/codacy/1c9ba18e61664f84885bd76d1468ced9.svg)](https://www.codacy.com/public/zortje/mysql-keeper_2)

[![VersionEye](https://img.shields.io/versioneye/d/php/zortje:mysql-keeper.svg?style=flat)](https://www.versioneye.com/php/zortje:mysql-keeper) [![Packagist](https://img.shields.io/packagist/dt/zortje/mysql-keeper.svg?style=flat)](https://packagist.org/packages/zortje/mysql-keeper)

## Installing via Composer

The recommended way to install MySQL Keeper is though [Composer](https://getcomposer.org/).

```JSON
{
    "require": {
        "zortje/mysql-keeper": "~0.0"
    }
}
```

## Usage

```PHP
$pdo = new PDO('mysql:host=127.0.0.1;dbname=myapp', 'root', '');

$database = DatabaseFactory::create($pdo);
$databaseResult = $database->getResult();
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

## Usage with Melody

[Melody](http://melody.sensiolabs.org) allows inline Composer requirements to simply execute PHP scripts.

mysql-keeper_myapp.php
```PHP
<?php

<<<CONFIG
packages:
    - "zortje/mysql-keeper": "~0.0"
CONFIG;

$pdo = new PDO('mysql:host=127.0.0.1;dbname=myapp', 'root', '');

$database = Zortje\MySQLKeeper\DatabaseFactory::create($pdo);

print_r($database->getResult());
```

Simply run:
```
$ melody run mysql-keeper_myapp.php
```

## Features

Detects the following issues

* Duplicate indices
* Missing primary key on auto_increment column
* Redundant unique index on primary key
* Redundant key index on primary key
* Inconsistent usage of collation between table and columns
