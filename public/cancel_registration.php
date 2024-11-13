<?php
session_start();
include('../includes/db_connect.php');
// Get event_id from URL
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Decode existing cookie data
$registered_events = isset($_COOKIE['registered_events']) ? json_decode($_COOKIE['registered_events'], true) : [];

// Check if the user had registered
if (($key = array_search($event_id, $registered_events)) !== false) {
    // Remove the event ID from the cookie array
    unset($registered_events[$key]);
    $registered_events = array_values($registered_events); // Reindex array
    setcookie('registered_events', json_encode($registered_events), time() + (86400 * 30), "/"); // Update cookie

    // Delete the participant record from the database
    $delete_query = $conn->prepare("DELETE FROM participants WHERE event_id = ?");
    $delete_query->bind_param("i", $event_id);
    $delete_query->execute();

    if ($delete_query->affected_rows > 0) {
        echo "<center> <p>You have successfuly Cancell registration.</p> </center?";
    } else {
        echo "<p>Event registration canceled, but no entry was found in the participant table.</p>";
    }

    $delete_query->close();
} else {
    echo "<center> <p>You were not registered for this event.</p> </center>";
}

echo '<center> <p><a href="index.php">Back to Events List</a></p> </center>';

$conn->close();
?>
