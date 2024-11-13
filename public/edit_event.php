<?php
session_start();
include('../includes/db_connect.php');
// Check if event_id is set
if (!isset($_GET['event_id'])) {
    header('Location: events.phpp');
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $date = $_POST['date'];
    $location = $_POST['location'];

    // Update event in the database
    $updateQuery = "UPDATE events SET title = ?, date = ?, location = ? WHERE event_id = ?";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->bind_param('sssi', $title, $date, $location, $event_id);
    
    if ($updateStmt->execute()) {
        header('Location: events.php');
        exit();
    } else {
        echo "Error updating event.";
    }
}
include('../views/edit_event.html');

?>
