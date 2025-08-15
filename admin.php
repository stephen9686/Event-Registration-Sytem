<?php
include 'db_connect.php';

// Fetch user and event count data
$query = "
    SELECT u.username, COALESCE(uec.event_count, 0) AS event_count
    FROM users u
    LEFT JOIN user_events_count uec ON u.id = uec.creator_id
";
$stmt = $pdo->prepare($query);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
    <style>
        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 2rem;
            background: linear-gradient(45deg, #f0f0f0, #3498db, #f0f0f0, #3498db);
            background-size: 400% 400%;
            animation: gradientAnimation 10s ease infinite;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        table {
            max-width: 600px;
            width: 100%;
            margin-top: 1rem;
        }

        h1, h2 {
            text-align: center;
        }

        a {
            margin-top: 1rem;
        }
    </style>
</head>

<body>
    <h1>Welcome to the Admin Page</h1>

    <a href="create_event.php">Create an Event</a>

    <h2>List of Users and Their Event Counts</h2>
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

    <a href="login.php">Logout</a>
</body>
</html>
