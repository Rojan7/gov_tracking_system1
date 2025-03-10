<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin_id'])) header('Location: admin_login.php');

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    
    // Fetch the credentials for the user
    $result = $conn->query("SELECT u.name, uc.credentials 
                            FROM users u 
                            JOIN user_credentials uc ON u.id = uc.user_id 
                            WHERE u.id = $user_id");
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        die("Credentials not found.");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Credentials</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-10 bg-gray-100">

    <h1 class="text-3xl mb-6">User Credentials</h1>

    <div class="bg-white p-6 rounded shadow mb-10">
        <h2 class="text-xl mb-4">Credentials for <?php echo $user['name']; ?></h2>
        <p><?php echo nl2br($user['credentials']); ?></p>
    </div>

    <div class="bg-white p-6 rounded shadow">
        <a href="admin_dashboard.php" class="bg-blue-500 text-white px-4 py-2 rounded">Back to Dashboard</a>
    </div>

</body>
</html>
