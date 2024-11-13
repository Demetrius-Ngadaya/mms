<?php
session_start();
include('../includes/db_connect.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $title = $_POST['title'];
    $date = $_POST['date'];
    $location = $_POST['location'];
    $status = "upcoming";

    // Insert new event into the database
    $query = "INSERT INTO events (title, date, location ,status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $title, $date, $location,$status);
    
    if ($stmt->execute()) {
        header('Location: events.php');
        exit();
    } else {
        echo "Error creating event.";
    }
}
include('../views/create_event.html');
?>


