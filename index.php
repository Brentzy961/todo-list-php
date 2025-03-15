<?php
require_once 'db.php';

// Items per page for pagination
$items_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start_from = ($page - 1) * $items_per_page;

// Get tasks count for pagination
$count_query = "SELECT COUNT(*) as total FROM tasks";
$count_result = mysqli_query($conn, $count_query);
$total_records = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_records / $items_per_page);

// Get tasks with pagination
$query = "SELECT * FROM tasks ORDER BY created_at DESC LIMIT $start_from, $items_per_page";
$result = mysqli_query($conn, $query);

// Handle messages
$success_message = isset($_GET['success']) ? $_GET['success'] : '';
$error_message = isset($_GET['error']) ? $_GET['error'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Todo List Application</h1>
        </header>

        <?php if (!empty($success_message)): ?>
            <div class="success-message"><?php echo htmlspecialchars($success_message); ?></div>
        <?php endif; ?>
        
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <div class="task-form">
            <h2>Add New Task</h2>
            <form action="add_task.php" method="POST" id="task-form">
                <div class="form-group">
                    <input type="text" name="task" id="task" placeholder="Enter your task here..." required>
                    <button type="submit" class="btn btn-add">Add Task</button>
                </div>
                <span id="task-error" class="error-message"></span>
            </form>
        </div>

        <div class="task-list">
            <h2>Your Tasks</h2>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <ul>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <li class="task-item <?php echo ($row['status'] == 'completed') ? 'completed' : ''; ?>">
                            <div class="task-content">
                                <span class="task-text"><?php echo htmlspecialchars($row['task']); ?></span>
                                <span class="task-date"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                            </div>
                            <div class="task-actions">
                                <?php if ($row['status'] == 'pending'): ?>
                                    <a href="complete_task.php?id=<?php echo $row['id']; ?>" class="btn btn-complete">Complete</a>
                                <?php endif; ?>
                                <a href="#" class="btn btn-edit" onclick="editTask(<?php echo $row['id']; ?>, '<?php echo addslashes($row['task']); ?>')">Edit</a>
                                <a href="delete_task.php?id=<?php echo $row['id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this task?')">Delete</a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                </ul>
                
                <!-- Pagination -->
                <?php if ($total_pages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page-1; ?>" class="page-link">&laquo; Previous</a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="page-link <?php echo ($page == $i) ? 'active' : ''; ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    
                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?php echo $page+1; ?>" class="page-link">Next &raquo;</a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
            <?php else: ?>
                <p class="no-tasks">No tasks found. Add a new task to get started!</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="edit-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Edit Task</h2>
            <form action="edit_task.php" method="POST">
                <input type="hidden" id="edit-task-id" name="id">
                <div class="form-group">
                    <input type="text" id="edit-task-text" name="task" required>
                </div>
                <button type="submit" class="btn btn-update">Update Task</button>
            </form>
        </div>
    </div>

    <script>
        // Form validation
        document.getElementById('task-form').addEventListener('submit', function(e) {
            const task = document.getElementById('task').value.trim();
            const errorElement = document.getElementById('task-error');
            
            if (task === '') {
                e.preventDefault();
                errorElement.textContent = 'Task cannot be empty!';
            } else {
                errorElement.textContent = '';
            }
        });

        // Edit task modal
        function editTask(id, task) {
            document.getElementById('edit-task-id').value = id;
            document.getElementById('edit-task-text').value = task;
            document.getElementById('edit-modal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('edit-modal').style.display = 'none';
        }

        // Close the modal when clicking outside of it
        window.onclick = function(event) {
            const modal = document.getElementById('edit-modal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>