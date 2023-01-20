<?php

$servername = base64_decode('bG9jYWxob3N0');
$username = base64_decode('bXNwYWRhZm8=');
$password = base64_decode('cHdwd3B3cHc=');
$dbname = base64_decode('bXNwYWRhZm8=');
global $conn;
$conn =  new mysqli($servername, $username, $password, $dbname);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
