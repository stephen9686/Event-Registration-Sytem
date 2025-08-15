<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';

// Fetch all users and count of events created
$query = "SELECT u.username, COUNT(e.id) AS event_count
          FROM users u
          LEFT JOIN events e ON u.id = e.user_id
          GROUP BY u.id";
$stmt = $pdo->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
</head>
<body>
    <h1>Admin Dashboard</h1>

    <a href="create_event.php">Create an Event</a> | <a href="logout.php">Logout</a>

    <h2>Users and Their Event Counts</h2>
    <table>
        <thead>
            <tr>
                <th>Username</th>
                <th>Event Count</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= (int)$user['event_count'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
