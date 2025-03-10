<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin_id'])) header('Location: admin_login.php');

// Add new activity
if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $department = $_POST['department'];
    $conn->query("INSERT INTO activities (title, description, department) VALUES ('$title', '$description', '$department')");
}

// Update application status
if (isset($_POST['update_status'])) {
    $app_id = $_POST['app_id'];
    $new_status = $_POST['status'];
    $conn->query("UPDATE applications SET status='$new_status' WHERE id=$app_id");
}

// Fetch all activities
$activities = $conn->query("SELECT * FROM activities");

// Fetch all applications with user and activity info
$applications = $conn->query("SELECT a.id as app_id, u.name as user_name, act.title as activity_title, a.status 
                              FROM applications a
                              JOIN users u ON a.user_id = u.id
                              JOIN activities act ON a.activity_id = act.id
                              ORDER BY a.id DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="p-10 bg-gray-100">

    <h1 class="text-3xl mb-6">Welcome, Admin</h1>
    <div class="text-right mb-6">
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded">Logout</a>
    </div>

    <!-- Add New Activity -->
    <div class="bg-white p-6 rounded shadow mb-10">
        <h2 class="text-xl mb-4">Add New Activity/Service</h2>
        <form method="POST" class="space-y-4">
            <input name="title" placeholder="Activity Title" class="w-full p-2 border rounded" required>
            <input name="description" placeholder="Description" class="w-full p-2 border rounded" required>
            <input name="department" placeholder="Department" class="w-full p-2 border rounded" required>
            <button name="add" class="bg-blue-500 text-white px-4 py-2 rounded">Add Activity</button>
        </form>
    </div>

    <!-- Show All Activities -->
    <div class="bg-white p-6 rounded shadow mb-10">
        <h2 class="text-xl mb-4">Available Activities/Services</h2>
        <?php if ($activities->num_rows > 0): ?>
            <ul class="space-y-2">
                <?php while ($act = $activities->fetch_assoc()): ?>
                    <li class="border p-4 rounded bg-gray-50">
                        <strong><?php echo $act['title']; ?></strong> (<?php echo $act['department']; ?>)
                        <p><?php echo $act['description']; ?></p>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No activities added yet.</p>
        <?php endif; ?>
    </div>

    <!-- User Applications and Status -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl mb-4">User Applications & Status</h2>
        <?php if ($applications->num_rows > 0): ?>
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="border p-2">User</th>
                        <th class="border p-2">Activity</th>
                        <th class="border p-2">Current Status</th>
                        <th class="border p-2">Change Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($app = $applications->fetch_assoc()): ?>
                        <tr>
                            <td class="border p-2"><?php echo $app['user_name']; ?></td>
                            <td class="border p-2"><?php echo $app['activity_title']; ?></td>
                            <td class="border p-2"><?php echo $app['status']; ?></td>
                            <td class="border p-2">
                                <form method="POST" class="flex space-x-2">
                                    <input type="hidden" name="app_id" value="<?php echo $app['app_id']; ?>">
                                    <select name="status" class="p-1 border rounded">
                                        <option value="Pending" <?php if ($app['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                                        <option value="Completed" <?php if ($app['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                                    </select>
                                    <button name="update_status" class="bg-green-500 text-white px-2 py-1 rounded">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No user applications found.</p>
        <?php endif; ?>
    </div>

</body>
</html>

