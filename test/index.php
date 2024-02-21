<?php
require_once __DIR__ . '/vendor/autoload.php';
use Duc\QueryBuilder\QueryBuilder;

$servername = "localhost";
$dbname = "query_builder_test";
$username = "root";
$password = "121212";

$config = [
    'host' => $servername,
    'dbname' => $dbname,
    'username' => $username,
    'password' => $password
];

$db = new QueryBuilder($config);

$data = $db->select('id', 'name')->from('users')->get();
print_r($data);


/*SELECT*/
// $data = $db->select('id', 'name')->from('users')->execute();
// print_r($data);



/*INSERT*/
// $data = $db->insert('users', array('id', 'name'), 'VALUES', array(12, 'aaron'), array(13, 'dragonsworn'))->execute();
// print_r($db->select('*')->from('users')->get());



/*DELETE*/
// $data = $db->delete('users', 'name', 'aaron')->execute();
// $data = $db->delete('users', 'id', '13')->execute();
// $data = $db->select('*')->from('users')->get();
// print_r($data);



/*UPDATE*/
// $data = $db->update('users', array('name' => 'dub'), array('id', '=', 1))->execute();
// $data = $db->select('*')->from('users')->get();
// print_r($data);
?>