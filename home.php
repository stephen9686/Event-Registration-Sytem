<?php
session_start();

// Redirect to login if user is not logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';

$userId = $_SESSION["user_id"];

// Fetch events created by the logged-in user
$query = "SELECT * FROM events WHERE user_id = ? ORDER BY event_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$userEvents = $stmt->fetchAll();

// Fetch events created by others
$query = "SELECT e.*, u.username FROM events e INNER JOIN users u ON e.user_id = u.id WHERE e.user_id != ? ORDER BY e.event_date DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$userId]);
$otherEvents = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Home</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
</head>
<body>
    <h1>Welcome to the Home Page</h1>

    <a href="create_event.php">Create an Event</a> | <a href="logout.php">Logout</a>

    <h2>Your Events</h2>
    <?php if ($userEvents): ?>
        <?php foreach ($userEvents as $event): ?>
            <p><strong>Title:</strong> <?= htmlspecialchars($event['title']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($event['description']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
            <?php if (!empty($event['image'])): ?>
                <img src="<?= htmlspecialchars($event['image']) ?>" style="max-width:200px;">
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No events created yet.</p>
    <?php endif; ?>

    <h2>Events by Others</h2>
    <?php if ($otherEvents): ?>
        <?php foreach ($otherEvents as $event): ?>
            <p><strong>Title:</strong> <?= htmlspecialchars($event['title']) ?></p>
            <p><strong>Description:</strong> <?= htmlspecialchars($event['description']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($event['event_date']) ?></p>
            <p><strong>Created by:</strong> <?= htmlspecialchars($event['username']) ?></p>
            <?php if (!empty($event['image'])): ?>
                <img src="<?= htmlspecialchars($event['image']) ?>" style="max-width:200px;">
            <?php endif; ?>
            <hr>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No events by other users.</p>
    <?php endif; ?>
</body>
</html>
