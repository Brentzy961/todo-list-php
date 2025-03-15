<?php
require_once 'db.php';

// Validate and process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task_id = $_POST['id'];
    $task = trim($_POST['task']);
    
    // Validate task
    if (empty($task)) {
        header("Location: index.php?error=Task cannot be empty!");
        exit;
    }
    
    // Update task in database
    $task = mysqli_real_escape_string($conn, $task);
    $query = "UPDATE tasks SET task = '$task' WHERE id = $task_id";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php?success=Task updated successfully!");
    } else {
        header("Location: index.php?error=" . urlencode("Error: " . mysqli_error($conn)));
    }
    
    exit;
}
?>