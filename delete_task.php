<?php
require_once 'db.php';

// Validate and process request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $task_id = $_GET['id'];
    
    // Delete the task
    $query = "DELETE FROM tasks WHERE id = $task_id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php?success=Task deleted successfully!");
    } else {
        header("Location: index.php?error=" . urlencode("Error: " . mysqli_error($conn)));
    }
} else {
    header("Location: index.php?error=Invalid task ID!");
}

exit;
?>