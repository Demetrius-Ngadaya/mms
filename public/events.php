

<?php
session_start();
include('../includes/db_connect.php');

$result = $conn->query("SELECT * FROM events  ORDER BY date ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/event.css">
    <title>Events List</title>
    
</head>
<body>
    <div class="container">
        <h2> List all Events</h2>

        <a href="create_event.php" class="btn-create">Create New Event</a>
        <a href="upcoming_events.php">View upcoming events</a>

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
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td>
                            <!-- Action buttons -->
                            <a href="view_participants.php?event_id=<?php echo $row['event_id']; ?>" class="action-btn view">View</a>
                            <a href="edit_event.php?event_id=<?php echo $row['event_id']; ?>" class="action-btn edit">Edit</a>
                            <a href="update_event.php?event_id=<?php echo $row['event_id']; ?>" class="action-btn update">Update</a>
                            <a href="delete_event.php?event_id=<?php echo $row['event_id']; ?>" class="action-btn delete">Delete</a>

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
