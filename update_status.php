<?php
// Include database configuration file
require 'config.php';
session_start();

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get task ID and checked status from POST data
    $taskId = $_POST['taskId'];
    $isChecked = $_POST['isChecked'] === 'true' ? 1 : 0; // Convert 'true'/'false' to 1/0

    // Update the task status in the database
    $sql = "UPDATE tasks SET is_complete = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ii', $isChecked, $taskId);
        if ($stmt->execute()) {
            echo "Task updated successfully";
        } else {
            echo "Error updating task: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
} else {
    echo "Invalid request method";
}
?>
