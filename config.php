<?php
$host = "localhost";
$user = "root";
$pass = "ecc123";
$dbname = "22ubc651";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
