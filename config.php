<?php
$host = 'database-1.cl42gqgioq5c.ap-south-1.rds.amazonaws.com';
$user = 'root';
$password = 'Pass1234';
$dbname = 'files_uploads';

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>