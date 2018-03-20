<?php
$host = 'localhost';
$username = 'avolvach';
$pass = 'neto1512';
$dbname = 'avolvach';
error_reporting(E_ALL);
$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $pass);
if (!$pdo)
{
    die('Could not connect');
}
$cookie_expiration_time = time() + 60 * 15;
?>
