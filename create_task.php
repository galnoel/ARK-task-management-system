<?php
// Include database configuration file
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming the token is stored in a session variable
    $token = $_SESSION['token'] ?? '';

    if (empty($token)) {
        echo "You must be logged in to create a task.";
        exit();
    }

    // Fetch user_id from the tokens table using the token
    $sql = "SELECT user_id FROM tokens WHERE token = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $token);+
        $stmt->execute();
        $stmt->bind_result($userId);
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

    $taskName = $_POST['taskName'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $createdBy = $userId;

    // Basic form validation
    if (empty($taskName)) {
        echo "Please provide a task name.";
        exit();
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert the new task into the database
        $sql = "INSERT INTO task (name, deadline, user_id) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssi", $taskName, $deadline, $createdBy);
            $stmt->execute();
            $teamId = $stmt->insert_id;
            $stmt->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }


        // Commit transaction
        $conn->commit();

        // Redirect to Teams page
        header("Location: tasks.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn->close();
}
?>
