<?php
session_start();
include('../includes/db_connect.php');

// Check if there is a search term or filter selected
$search = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'upcoming'; // Default filter to 'upcoming'

// Prepare SQL query with optional search and filter
$sql = "SELECT * FROM events WHERE status = ? ";

// Add search condition if a search term is provided
if ($search) {
    $sql .= " AND (title LIKE ? OR location LIKE ?)";
}

// Add order by date
$sql .= " ORDER BY date ASC";

// Prepare and execute query
$stmt = $conn->prepare($sql);

if ($search) {
    $searchTerm = '%' . $search . '%';
    $stmt->bind_param('sss', $filter, $searchTerm, $searchTerm); // Bind filter and search parameters
} else {
    $stmt->bind_param('s', $filter); // Bind only filter parameter if no search
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/event.css">
    <title>Events List</title>
<body>
    <div class="container">
        <h2> List of Upcoming Events</h2>

        <!-- Search and Filter Form -->
        <div class="search-container">
            <form method="GET" action="upcoming_events.php">
                <input type="text" name="search" placeholder="Search by title or location" value="<?php echo htmlspecialchars($search); ?>">
                <select name="filter">
                    <option value="upcoming" <?php echo $filter == 'upcoming' ? 'selected' : ''; ?>>Upcoming</option>
                    <option value="completed" <?php echo $filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo $filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
                <button type="submit" class="btn-create">Search</button>
            </form>
        </div>

        <a href="create_event.php" class="btn-create">Create New Event</a>
        <a href="events.php">List of all events</a>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Participants</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $serialNumber = 1; 
                while ($row = $result->fetch_assoc()) { 
                    // Query to get participant count for each event
                    $event_id = $row['event_id'];
                    $participantQuery = "SELECT COUNT(*) AS participant_count FROM participants WHERE event_id = ?";
                    $stmt2 = $conn->prepare($participantQuery);
                    $stmt2->bind_param('i', $event_id);
                    $stmt2->execute();
                    $participantResult = $stmt2->get_result();
                    $participantCount = $participantResult->fetch_assoc()['participant_count'];
                ?>
                    <tr>
                        <td><?php echo $serialNumber++; ?></td> 
                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['location']); ?></td>
                        <td><?php echo $participantCount; ?></td> 
                        <td>
                            <!-- Action buttons -->
                            <a href="view_participants.php?event_id=<?php echo $row['event_id']; ?>" class="action-btn view">View</a>
                            <a href="edit_event.php?event_id=<?php echo $row['event_id']; ?>" class="action-btn edit">Edit</a>
                            <a href="update_event.php?event_id=<?php echo $row['event_id']; ?>" class="action-btn update">Update</a>
                            <a href="delete_event.php?event_id=<?php echo $row['event_id']; ?>" class="action-btn delete">Delete</a>
                        </td>
                    </tr>
                <?php 
                    $stmt2->close(); // Close the participant query statement
                } ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
