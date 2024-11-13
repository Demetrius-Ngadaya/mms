<?php
session_start();
include('../includes/db_connect.php');

// Check if event_id is set
if (!isset($_GET['event_id'])) {
    header('Location: events.php');
    exit();
}

$event_id = $_GET['event_id'];
$query = "SELECT * FROM events WHERE event_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $event_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: events.php');
    exit();
}

$event = $result->fetch_assoc();

// For demonstration, let's assume we're updating the event status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Example: Update event status or any other field
    $status = $_POST['status'];

    // Update event in the database
    $updateQuery = "UPDATE events SET status = ? WHERE event_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('si', $status, $event_id);
    
    if ($updateStmt->execute()) {
        header('Location: events.php');
        exit();
    } else {
        echo "Error updating event.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">

    <title>Update Event</title>
</head>
<body>
    <form method="POST">
    <h2>Update Event</h2>

        <label for="status">Event Status:</label>
        <select name="status" id="status">
            <option value="Upcoming" <?php if ($event['status'] == 'Upcoming') echo 'selected'; ?>>Upcoming</option>
            <option value="Completed" <?php if ($event['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
            <option value="Cancelled" <?php if ($event['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
        </select><br>

        <button type="submit">Update Status</button>
    </form>
</body>
</html>
