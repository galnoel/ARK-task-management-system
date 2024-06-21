<?php
// Include database configuration file
require 'config.php';
session_start();

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming the token is stored in a session variable
    $token = $_SESSION['token'] ?? '';

    // Get task ID from POST data
    $taskId = $_POST['taskId'];

    // Fetch user_id from the tokens table using the token
    $sqlToken = "SELECT user_id FROM tokens WHERE token = ?";
    if ($stmtToken = $conn->prepare($sqlToken)) {
        $stmtToken->bind_param('s', $token);
        $stmtToken->execute();
        $stmtToken->bind_result($userId);
        if ($stmtToken->fetch()) {
            // Check if the task belongs to the authenticated user
            $sqlTask = "SELECT created_by FROM tasks WHERE id = ?";
            if ($stmtTask = $conn->prepare($sqlTask)) {
                $stmtTask->bind_param('i', $taskId);
                $stmtTask->execute();
                $stmtTask->bind_result($taskCreatedBy);
                if ($stmtTask->fetch()) {
                    if ($taskCreatedBy == $userId) {
                        // Delete the task from the database
                        $sqlDelete = "DELETE FROM tasks WHERE id = ?";
                        if ($stmtDelete = $conn->prepare($sqlDelete)) {
                            $stmtDelete->bind_param('i', $taskId);
                            if ($stmtDelete->execute()) {
                                echo "Task deleted successfully";
                            } else {
                                echo "Error deleting task: " . $stmtDelete->error;
                            }
                            $stmtDelete->close();
                        } else {
                            echo "Error preparing delete statement: " . $conn->error;
                        }
                    } else {
                        echo "You are not authorized to delete this task";
                    }
                } else {
                    echo "Task not found";
                }
                $stmtTask->close();
            } else {
                echo "Error preparing task statement: " . $conn->error;
            }
        } else {
            echo "Invalid token. Please log in again.";
        }
        $stmtToken->close();
    } else {
        echo "Error preparing token statement: " . $conn->error;
    }
} else {
    echo "Invalid request method";
}
?>
