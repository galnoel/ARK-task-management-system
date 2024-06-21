<?php
// Include database configuration file
require 'config.php';
session_start();

date_default_timezone_set('Asia/Makassar');


// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $token = $_SESSION['token'] ?? '';

    if (empty($token)) {
        echo "You must be logged in to create a task.";
        exit();
    }

    // Fetch user_id and role from the tokens table using the token
    $sql = "SELECT users.id, users.role FROM tokens JOIN users ON tokens.user_id = users.id WHERE tokens.token = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->bind_result($userId, $role);
        if ($stmt->fetch()) {
            $stmt->close();
        } else {
            echo "Invalid token. Please log in again.";
            $stmt->close();
            exit();
        }
    } else {
        echo "Error preparing token statement: " . $conn->error;
        exit();
    }

    // Get task ID and checked status from POST data
    $taskId = $_POST['task_id'];
    $isChecked = isset($_POST['is_complete']) ? 1 : 0; // Convert 'true'/'false' to 1/0
    // Determine the completed_at value
    $completedAt = $isChecked ? date('Y-m-d H:i:s') : NULL;
    
    // Update the task status in the database
    $sql = "UPDATE task SET is_complete = ?, completed_at = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('isi', $isChecked, $completedAt, $taskId);
        if ($stmt->execute()) {
            echo "Task updated successfully";
            if ($role === 'admin') {
                header("Location: HomeAdmin.php"); // Replace with your admin page
            } else {
                header("Location: HomeMember.php"); // Replace with your main page
            }
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
