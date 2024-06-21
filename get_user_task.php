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
        // Close the token statement
        $stmtToken->close();

        // Query tasks for the authenticated user
        $sqlTasks = "SELECT id, name, deadline, is_complete, completed_at, created_at 
                     FROM task
                     WHERE user_id = ?";
        if ($stmtTasks = $conn->prepare($sqlTasks)) {
            $stmtTasks->bind_param('i', $userId);
            $stmtTasks->execute();
            $resultTasks = $stmtTasks->get_result(); // Get the result set

            // Fetching tasks into an array
            $tasks = array();
            while ($row = $resultTasks->fetch_assoc()) {
                $tasks[] = $row;
            }

            $stmtTasks->close();
        } else {
            echo "Error preparing tasks statement: " . $conn->error;
        }
    } else {
        echo "Invalid token. Please log in again.";
    }
    // Close the token statement if not already closed
    // if (isset($stmtToken)) {
    //     $stmtToken->close();
    // }
} else {
    echo "Error preparing token statement: " . $conn->error;
}

// Close connection
$conn->close();

// Output tasks as JSON or process as needed
// echo json_encode($tasks);
?>
