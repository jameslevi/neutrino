# Neutrino

![](https://img.shields.io/badge/packagist-v1.0.0-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a) ![](https://img.shields.io/badge/license-MIT-informational?style=flat&logo=<LOGO_NAME>&logoColor=white&color=2bbc8a)  

Is a simple database library using PDO in PHP.

## Supported Drivers
1. MySQL
2. Microsoft SQL Server

## Installation
You can install via composer.
```
composer require jameslevi/neutrino
```
Add the composer autoload to your project.
```php
require_once __DIR__ . '/vendor/autoload.php';
```
Import neutrino into your project.
```php
use Graphite\Component\Neutrino\Neutrino;
```

## The Basics
```php
<?php

// add the composer autoload.
require_once __DIR__ . '/vendor/autoload.php';

// import neutrino into your project.
use Graphite\Component\Neutrino\Neutrino;

// create a new neutrino instance.
$db = new Neutrino('mysql');

// set the database to access.
$db->setDatabase('your_database');

// set credentials for authentication.
$db->setUsername('user1');
$db->setPassword('your_password');

// set server port.
$db->setPort(3306);

// establish connection.
$db->connect();

// check if connection was established.
if(!$db->isConnected()) {
    die('Connection Failed');
}

// set SQL query to execute.
$query = $db->query('SELECT * FROM users WHERE id = :id');

// set placeholder value.
$query->addIntParam('id', 1);

// get the query result.
$fetch = $query->get();

// Convert result to json.
echo $fetch->toJson();

// close the connection.
$db->close();
```

## Establishing Connection
You can establish connection by providing the name of your database, server name, username and password.
```php
// create a new neutrino instance.
$db = new Neutrino('sqlsrv');

// set the database to use.
$db->setDatabase('mydatabase');

// set authentication username.
$db->setUsername('your_username');

// set authentication password.
$db->setPassword('your_password');

// set port number if required.
$db->setPort(1433);

// establish connection.
$db->connect();
```
Always remember to close the connection each time after use.
```php
$db->close();
```

## Establishing Connection using DSN String
```php
// create a new neutrino instance.
$db = new Neutrino('sqlsrv');

// set dsn string.
$db->setDsn('server=localhost;database=your_database');

// Set username if required.
$db->setUsername('your_username');

// Set password if required.
$db->setPassword('your_password');

// establish connection.
$db->connect();
```

## Basic Query
Use get method if expecting results such as SELECT queries.
```php
$query = $db->query('SELECT * FROM members')->get();
```
Use exec method if no result is expected such as UPDATE, INSERT or DELETE queries.
```php
$db->query('DELETE FROM members WHERE id = 1')->exec();
```

## Parameters
You can bind values indirectly to your SQL script.
```php
// your SQL script.
$query = $db->query('SELECT * FROM members WHERE id = :id LIMIT :start, :offset');

// bind values into your SQL script.
$query->addParam('id', 1)
      ->addParam('start', 0)
      ->addParam('offset', 10);

// execute the query.
$fetch = $query->get();
```

## Parameter Data Types
You can declare the data type of each parameters.
```php
$query->addParam('id', 1, PDO::PARAM_INT);
```
Parameter data types supports string, integer, boolean and null.
```php
$db->addStringParam('id', '1'); // Value has string data type.
$db->addIntegerParam('id', 1); // Value has integer data type.
$db->addBooleanParam('id', true); // Value has boolean data type.
$db->addNullParam('id', null); // Value has null data type.
```

## Fetching Results
You can return the list of result using fetch method.
```php
$rows = $db->query('SELECT email FROM members')->get()->fetch();

// list all emails.
foreach($rows as $row)
{
    echo $row->email . '<br>';
}
```

## Get Result Helper Methods
You can get row by index number in a result list.
```php
$get = $db->query('SELECT email FROM members')->get();

// Get the first row from result.
echo $get->get(0)->email;
```
You can easily get the first and last row from the result list.
```php
$get = $db->query('SELECT email FROM members')->get();

// Get the first email from the results.
echo $get->first()->email;

// Get the last email from the results.
echo $get->last()->email;
```
You can return a list of values from a single column.
```php
var_dump($get->pluck('email')); // Get all emails from the query.
```
You can return the column names available from the result.
```php
var_dump($get->columnNames());
```
You can determine the number of rows from the result.
```php
echo $get->numRows();
```
Return result as array.
```php
var_dump($get->toArray());
```
Return result as json.
```php
echo $get->toJson();
```

## PDO Attributes
You can set PDO attributes before establishing the connection.
```php
$db->addOption(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
```
## Error Handling
Speficy how the driver will report errors. Values can be *silent*, *warning* or *exception*.
```php
$db->setErrorMode('exception');

// or

$db->errorModeException();
```

## Column Name Cases
Specify the case of the column names.
```php
$db->lowercase(); // Causes column names to lowercase.
$db->natural(); // Display column names as returned by the database.
$db->uppercase(); // Causes column names to uppercase.
```

## Transformations
Convert numeric values into string.
```php
$db->stringify();
```
Convert empty string to null.
```php
$db->setEmptyStringToNull();
```
Convert null to empty string.
```php
$db->setNullToEmptyString();
```

## Buffer Size
Value might vary depending on driver.
```php
$db->setMaxBufferSize(1024);
```

## Buffered Queries
Force queries to be buffered. Only available in MySQL.
```php
$db->useBufferedQuery();
```

## Timeout
Set the query timeout in seconds. Only available in Microsoft SQL Server.
```php
$db->setTimeout(20);
```

## Contribution
For issues, concerns and suggestions, you can email James Crisostomo via nerdlabenterprise@gmail.com.

## License
This package is an open-sourced software licensed under [MIT](https://opensource.org/licenses/MIT) License.
