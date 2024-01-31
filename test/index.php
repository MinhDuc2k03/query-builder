<?php
namespace Duc;
require '../vendor/autoload.php';
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
$data = $db->select('user_id', 'user_email')->from('users', 'admins')->execute();
?>