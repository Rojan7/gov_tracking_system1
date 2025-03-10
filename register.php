<?php
include 'config.php';
session_start();

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    $stmt->execute();

    echo "<script>alert('Registration successful. Please login.'); window.location='login.php';</script>";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <form method="POST" class="bg-white p-8 rounded shadow-md w-96">
        <h2 class="text-2xl mb-4">Register</h2>
        <input type="text" name="name" placeholder="Name" required class="w-full p-2 mb-4 border rounded">
        <input type="email" name="email" placeholder="Email" required class="w-full p-2 mb-4 border rounded">
        <input type="password" name="password" placeholder="Password" required class="w-full p-2 mb-4 border rounded">
        <button name="register" class="bg-blue-500 w-full text-white py-2 rounded">Register</button>
        <p class="mt-4 text-center">Already registered? <a href="login.php" class="text-blue-600">Login</a></p>
    </form>
</body>
</html>
