<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate and insert participant
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $event_id = $_POST['event_id'];

    $stmt = $conn->prepare("INSERT INTO participants (name, email, contact, event_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $contact, $event_id);
    $stmt->execute();
    echo "Registration successful!";
}
?>