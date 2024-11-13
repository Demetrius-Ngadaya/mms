<?php
session_start();
include('../includes/db_connect.php');

// Get event_id from URL
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Fetch the event details from the database
$event = null;
if ($event_id > 0) {
    $stmt = $conn->prepare("SELECT * FROM events WHERE event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $event = $result->fetch_assoc();
    }
    $stmt->close();
}

// Check if the user is already registered (cookie-based check)
$registered_events = isset($_COOKIE['registered_events']) ? json_decode($_COOKIE['registered_events'], true) : [];

// Initialize error and success messages
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = $_POST['name'];
    $user_email = $_POST['email'];
    $user_phone = $_POST['phone'];

    // validation for phone and email
    if (!preg_match("/^[0-9]{10}$/", $user_phone)) {
        $error = "Phone number must be 10 digits long and contain only numbers.";
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Insert the registration data into the database (participants table)
        $stmt = $conn->prepare("INSERT INTO participants (name, email, contact, event_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $user_name, $user_email, $user_phone, $event_id);
        
        if ($stmt->execute()) {
            $registered_events[] = $event_id; // Add the event_id to the registered events list
            setcookie('registered_events', json_encode($registered_events), time() + (86400 * 30), "/"); // Update cookie

            $success = "Registration successful!";
        } else {
            $error = "Failed to register. Please try again later.";
        }

        $stmt->close();
    }
}

// If no event found, display an error message
if (!$event) {
    $error = "Event not found!";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/register.css">

    <title>Register for Event</title>
    
</head>
<body>
<div class="container">
    <h2>Register for Event: <?php echo htmlspecialchars($event['title']); ?></h2>

    <?php if ($error): ?>
        <p class="message"><?php echo $error; ?></p>
    <?php elseif ($success): ?>
        <p class="message success"><?php echo $success; ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="text" name="phone" placeholder="Your Phone Number" required pattern="\d{10}" title="Phone number must be 10 digits long" >
        <input type="email" name="email" placeholder="Your Email" required>
        <button type="submit">Register</button>
    </form>

    <p><a href="index.php">Back to Events List</a></p>
</div>
</body>
</html>
