<?php
session_start();
include('../includes/db_connect.php');

$result = $conn->query("SELECT * FROM events WHERE status !='Completed' ORDER BY date ASC");

// Check if a registration cookie exists and decode it
$registered_events = isset($_COOKIE['registered_events']) ? json_decode($_COOKIE['registered_events'], true) : [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/event.css">

    <title>List of all Events</title>
    
</head>
<body>
    <div class="container">
        <p><a href="login.php">Organizer login</a></p>
        <h2>Event List</h2>

        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) {
                    $event_id = $row['event_id'];
                    $is_registered = in_array($event_id, $registered_events);
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td>
                            <?php if ($is_registered) { ?>
                                <!-- Cancel Registration button -->
                                <a href="cancel_registration.php?event_id=<?php echo $event_id; ?>" class="action-btn cancel">Cancel Registration</a>
                            <?php } else { ?>
                                <!-- Register button -->
                                <a href="register_event.php?event_id=<?php echo $event_id; ?>" class="action-btn register">Register</a>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$conn->close();
?>
