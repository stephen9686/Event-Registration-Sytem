<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';

$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId = $_SESSION["user_id"];
    $title = trim($_POST["title"]);
    $description = trim($_POST["description"]);
    $eventDate = $_POST["event_date"];
    $phone_number = trim($_POST["phone_number"]);
    $email_id = trim($_POST["email_id"]);

    $today = date("Y-m-d");

    if ($eventDate < $today) {
        $successMessage = "You cannot select a date in the past.";
    } else {
        $imagePath = "";

        // Ensure uploads folder exists
        $uploadDir = "uploads/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!empty($_FILES["image"]["name"])) {
            $imageName = basename($_FILES["image"]["name"]);
            $imagePath = $uploadDir . $imageName;
            move_uploaded_file($_FILES["image"]["tmp_name"], $imagePath);
        }

        // Insert event into DB
        $query = "INSERT INTO events (user_id, title, description, event_date, image, phone_number, email_id, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$userId, $title, $description, $eventDate, $imagePath, $phone_number, $email_id]);

        // Redirect to home.php with success alert
        echo '<script>alert("Event created successfully!"); window.location.href="home.php";</script>';
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.min.css" />
</head>
<body>
    <h1>Create an Event</h1>

    <?php if(!empty($successMessage)) echo "<p style='color:red;'>$successMessage</p>"; ?>

    <form method="post" action="create_event.php" enctype="multipart/form-data">
        <label>Title:</label>
        <input type="text" name="title" required><br>

        <label>Description:</label>
        <textarea name="description" required></textarea><br>

        <label>Event Date:</label>
        <input type="date" name="event_date" required><br>

        <label>Phone Number:</label>
        <input type="text" name="phone_number" required><br>

        <label>Email ID:</label>
        <input type="email" name="email_id" required><br>

        <label>Event Image:</label>
        <input type="file" name="image"><br><br>

        <input type="submit" value="Create Event">
        <a href="home.php">Cancel</a>
    </form>
</body>
</html>
