<?php
require_once 'db.php';

// Validate and process request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $task_id = $_GET['id'];
    
    // Mark task as completed
    $query = "UPDATE tasks SET status = 'completed' WHERE id = $task_id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php?success=Task marked as completed!");
    } else {
        header("Location: index.php?error=" . urlencode("Error: " . mysqli_error($conn)));
    }
} else {
    header("Location: index.php?error=Invalid task ID!");
}

exit;
?>