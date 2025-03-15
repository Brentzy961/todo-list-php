<?php
require_once 'db.php';

// Validate and process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $task = trim($_POST['task']);
    
    // Validate task
    if (empty($task)) {
        header("Location: index.php?error=Task cannot be empty!");
        exit;
    }
    
    // Insert task into database
    $task = mysqli_real_escape_string($conn, $task);
    $query = "INSERT INTO tasks (task) VALUES ('$task')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: index.php?success=Task added successfully!");
    } else {
        header("Location: index.php?error=" . urlencode("Error: " . mysqli_error($conn)));
    }
    
    exit;
}
?>