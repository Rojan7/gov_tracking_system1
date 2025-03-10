<?php include 'config.php'; session_start();
if (!isset($_SESSION['admin_id'])) header('Location: admin_login.php');
$id = $_GET['id'];
$conn->query("UPDATE applications SET status='Approved' WHERE id=$id");
header('Location: admin_dashboard.php');
?>