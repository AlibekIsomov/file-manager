<?php
$host = '127.0.0.1';
$port = '5432';
$dbname = 'idkdb';
$username = 'postgres';
$password = 'y@suk321';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
