<?php
$conn = new mysqli('localhost', 'root', '', 'gov_tracking');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>