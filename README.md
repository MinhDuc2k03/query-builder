# Query Builder Package PHP



## Installation
```
composer require duc/query-builder
```


## How to use


### Use Composer Autoloader:
```php
require_once __DIR__ . '/vendor/autoload.php';
use Duc\QueryBuilder\QueryBuilder;
```


### Change your information for $config:
```php
$servername = "localhost";
$dbname = "query_builder_test";
$username = "root";
$password = "12345678";

$config = [
    'host' => $servername,
    'dbname' => $dbname,
    'username' => $username,
    'password' => $password
];
```


### Initialize QueryBuilder:
```php
$db = new QueryBuilder($config);
```


### Usages:

#### Select
```php
// Trả về dữ liệu từ CSDL
$data = $db->select('id', 'name')->from('users')->get();
print_r($data);     //Dữ liệu lấy được

// Chạy query rồi trả về query đó
$query = $db->select('id', 'name')->from('users')->execute();
print_r($query);     //SELECT id, name FROM users
```

#### Insert
```php
//INSERT INTO users (id, name) VALUES (2, 'duc'), (3, 'hoa')
$query = $db->insert('users', array('id', 'name'), 'VALUES', array(2, 'duc'), array(3, 'hoa'))->execute();   
```

#### Delete
```php
//DELETE FROM users WHERE id=4
$query = $db->delete('users', 'id', 4)->execute();
```

#### Update
```php
//UPDATE users SET name = 'trung' WHERE id = 1
$query1 = $db->update('users', array('name' => 'trung'), array('id', '=', 1))->execute();

//UPDATE users SET name = 'trung', age = 1 WHERE id = 1
$query2 = $db->update('users', array('name' => 'trung', 'age' => 1), array('id', '=', 1))->execute();
```