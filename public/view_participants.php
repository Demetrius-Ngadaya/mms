<?php
session_start();
include('../includes/db_connect.php');

// Get event_id from URL
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;

// Fetch the event details from the database to display the event name
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

// Fetch participants for this event
$participants = [];
if ($event_id > 0) {
    $stmt = $conn->prepare("SELECT p.name, p.email, p.contact FROM participants p WHERE p.event_id = ?");
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $participants[] = $row;
    }
    $stmt->close();
}

// If no event found, display an error message
if (!$event) {
    die("Event not found!");
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/event.css">

    <title>Participants for Event: <?php echo htmlspecialchars($event['title']); ?></title>
  
</head>
<body>
<div class="container">
    <?php if ($event): ?>
        <h2>Participants for Event: <?php echo htmlspecialchars($event['title']); ?></h2>
    <?php else: ?>
        <h2>Error: Event Not Found</h2>
    <?php endif; ?>

    <?php if (empty($participants)): ?>
        <center><p class="message">No participants registered for this event .</p></center>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>#</th> 
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $serial = 1; 
                foreach ($participants as $participant): ?>
                    <tr>
                        <td><?php echo $serial++; ?></td> 
                        <td><?php echo htmlspecialchars($participant['name']); ?></td>
                        <td><?php echo htmlspecialchars($participant['email']); ?></td>
                        <td><?php echo htmlspecialchars($participant['contact']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <center><p class="back-link"><a href="upcoming_events.php">Back to Events List</a></p></center>
</div>
</body>
</html>
