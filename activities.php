<?php
include 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) header('Location: login.php');

$user_id = $_SESSION['user_id'];
$message = '';

// Handle activity application
if (isset($_POST['apply'])) {
    $activity_id = $_POST['activity_id'];

    // Check if user already applied
    $check = $conn->query("SELECT * FROM applications WHERE user_id=$user_id AND activity_id=$activity_id");

    if ($check->num_rows > 0) {
        $message = "You have already applied for this activity!";
    } else {
        // Insert into the database without credentials
        $stmt = $conn->prepare("INSERT INTO applications (user_id, activity_id, status) VALUES (?, ?, 'Pending')");
        $stmt->bind_param("ii", $user_id, $activity_id);
        $stmt->execute();

        $message = "Applied successfully!";
    }
}

// Fetch all activities with user's application status
$activities = $conn->query("SELECT a.*, 
    (SELECT status FROM applications WHERE user_id=$user_id AND activity_id=a.id LIMIT 1) as user_status 
    FROM activities a");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Activities</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-10 bg-gray-100">

    <!-- Logout Button -->
    <div class="text-right mb-6">
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded">Logout</a>
    </div>

    <h1 class="text-3xl mb-6">Available Activities</h1>

    <!-- Show confirmation message -->
    <?php if ($message): ?>
        <div class="bg-green-100 text-green-700 p-4 rounded mb-6"><?php echo $message; ?></div>
    <?php endif; ?>

    <!-- List of activities -->
    <?php while ($row = $activities->fetch_assoc()) { ?>
        <div class="border p-4 rounded mb-4 bg-white shadow">
            <h2 class="text-xl font-bold"><?php echo $row['title']; ?></h2>
            <p class="mb-2"><?php echo $row['description']; ?></p>

            <!-- If user has already applied, show status -->
            <?php if ($row['user_status']): ?>
                <p class="text-yellow-600 font-semibold">Status: <?php echo $row['user_status']; ?></p>
            <?php else: ?>
                <!-- Apply form without file upload -->
                <form method="POST" class="mt-2">
                    <input type="hidden" name="activity_id" value="<?php echo $row['id']; ?>">
                    <button name="apply" class="bg-blue-500 text-white px-4 py-2 rounded">Apply</button>
                </form>
            <?php endif; ?>
        </div>
    <?php } ?>

</body>
</html>
