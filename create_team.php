<?php
// Include database configuration file
require 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming the token is stored in a session variable
    $token = $_SESSION['token'] ?? '';

    if (empty($token)) {
        echo "You must be logged in to create a team.";
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

    $teamName = $_POST['teamName'] ?? '';
    $teamDescription = $_POST['teamDescription'] ?? '';
    $createdBy = $userId;

    // Basic form validation
    if (empty($teamName)) {
        echo "Please provide a team name.";
        exit();
    }

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Insert the new team into the database
        $sql = "INSERT INTO teams (name, description, created_by) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssi", $teamName, $teamDescription, $createdBy);
            $stmt->execute();
            $teamId = $stmt->insert_id;
            $stmt->close();
        } else {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        // Insert the membership record into the database
        $sql = "INSERT INTO memberships (user_id, team_id, role) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $role = 'admin';
            $stmt->bind_param("iis", $createdBy, $teamId, $role);
            $stmt->execute();
            $stmt->close();
        } else {
            throw new Exception("Error preparing membership statement: " . $conn->error);
        }

        // Commit transaction
        $conn->commit();

        // Redirect to Teams page
        header("Location: Teams.html");
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
