<?php
session_start();
include('../includes/db_connect.php');

// Check if event_id is provided
if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $event_id = $_GET['event_id'];

    // Prepare the DELETE query for the event
    $sql = "DELETE FROM events WHERE event_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $event_id);

    // Execute the query
    if ($stmt->execute()) {
        // Optionally, you can also delete participants related to this event if needed
        // $sql_delete_participants = "DELETE FROM participants WHERE event_id = ?";
        // $stmt_participants = $conn->prepare($sql_delete_participants);
        // $stmt_participants->bind_param('i', $event_id);
        // $stmt_participants->execute();

        // Redirect back to the events list with a success message
        $_SESSION['message'] = "Event deleted successfully.";
        header("Location: upcoming_events.php"); // Redirect to the upcoming events page
        exit();
    } else {
        // If deletion fails, set an error message
        $_SESSION['message'] = "Failed to delete event. Please try again.";
        header("Location: upcoming_events.php");
        exit();
    }
} else {
    // If no event_id is provided or it's invalid, redirect to the events list
    $_SESSION['message'] = "Invalid event ID.";
    header("Location: upcoming_events.php");
    exit();
}

$stmt->close();
$conn->close();
?>
