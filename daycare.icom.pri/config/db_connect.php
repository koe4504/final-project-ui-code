<?php
$host = "localhost";
$dbname = "daycare_management";
$username = "dcdb_user"; 
$password = "/\*12457***5/7593614/\"; 

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
