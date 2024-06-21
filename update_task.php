<?php
// Include database configuration file
require 'config.php';
session_start();

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get task ID from POST data
    $taskId = $_POST['taskId'];

    // Initialize variables to store updated values
    $taskName = $_POST['taskName'] ?? null;
    $deadline = $_POST['deadline'] ?? null;

    // Fetch existing task data from database to retain unchanged values
    $sqlFetch = "SELECT name, deadline FROM tasks WHERE id = ?";
    if ($stmtFetch = $conn->prepare($sqlFetch)) {
        $stmtFetch->bind_param('i', $taskId);
        $stmtFetch->execute();
        $stmtFetch->bind_result($currentName, $currentDeadline);
        if ($stmtFetch->fetch()) {
            // Update variables with current data if user input is empty
            $taskName = $taskName ?? $currentName;
            $deadline = $deadline ?? $currentDeadline;

        } else {
            echo "Task not found";
            exit();
        }
        $stmtFetch->close();
    } else {
        echo "Error fetching task details: " . $conn->error;
        exit();
    }

    // Update the task in the database
    $sqlUpdate = "UPDATE tasks SET name = ?, deadline = ? WHERE id = ?";
    if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
        $stmtUpdate->bind_param('ssi', $taskName, $deadline, $taskId);
        if ($stmtUpdate->execute()) {
            echo "Task updated successfully";
        } else {
            echo "Error updating task: " . $stmtUpdate->error;
        }
        $stmtUpdate->close();
    } else {
        echo "Error preparing update statement: " . $conn->error;
    }
} else {
    echo "Invalid request method";
}
?>
