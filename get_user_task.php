<?php
// Include database configuration file
require 'config.php';
session_start();

// Check if the user is authenticated
$token = $_SESSION['token'] ?? '';

// Fetch user_id from the tokens table using the token
$sqlToken = "SELECT user_id FROM tokens WHERE token = ?";
if ($stmtToken = $conn->prepare($sqlToken)) {
    $stmtToken->bind_param('s', $token);
    $stmtToken->execute();
    $stmtToken->bind_result($userId);
    if ($stmtToken->fetch()) {
        // Query tasks for the authenticated user
        $sqlTasks = "SELECT name, deadline, is_complete, completed_at, created_at, updated_at 
                     FROM tasks 
                     WHERE created_by = ?";
        if ($stmtTasks = $conn->prepare($sqlTasks)) {
            $stmtTasks->bind_param('i', $userId);
            $stmtTasks->execute();
            $stmtTasks->bind_result($taskId, $taskName, $taskDeadline, $isComplete, $completedAt, $createdAt, $updatedAt);

            // Fetching tasks into an array
            $tasks = array();
            while ($stmtTasks->fetch()) {
                $task = array(
                    'name' => $taskName,
                    'deadline' => $taskDeadline,
                    'is_complete' => $isComplete,
                    'completed_at' => $completedAt,
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt
                );
                $tasks[] = $task;
            }

            // Output tasks as JSON or process as needed
            echo json_encode($tasks);

            $stmtTasks->close();
        } else {
            echo "Error preparing tasks statement: " . $conn->error;
        }
    } else {
        echo "Invalid token. Please log in again.";
    }
    $stmtToken->close();
} else {
    echo "Error preparing token statement: " . $conn->error;
}

// Close connection
$conn->close();
?>
      