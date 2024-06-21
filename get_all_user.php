<?php
// Include database configuration file
require 'config.php';
session_start();

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming the token is stored in a session variable
    $token = $_SESSION['token'] ?? '';

    if (empty($token)) {
        echo json_encode(array('error' => 'You must be logged in to view users.'));
        exit();
    }

    // Fetch user_id and role from the tokens table using the token
    $sql = "SELECT user_id, role FROM tokens WHERE token = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->bind_result($userId, $role);
        if ($stmt->fetch()) {
            $stmt->close();

            // Check if the user is admin
            if ($role !== 'admin') {
                echo json_encode(array('error' => 'You do not have permission to view users.'));
                exit();
            }
        } else {
            echo json_encode(array('error' => 'Invalid token. Please log in again.'));
            $stmt->close();
            exit();
        }
    } else {
        echo json_encode(array('error' => 'Error preparing token statement: ' . $conn->error));
        exit();
    }

    // SQL query to fetch all users
    $sqlUsers = "SELECT name, email, role, created_at FROM users";

    // Prepare and execute the SQL statement
    if ($resultUsers = $conn->query($sqlUsers)) {
        $users = array();
        while ($user = $resultUsers->fetch_assoc()) {
            $users[] = $user;
        }
        $resultUsers->free();
        echo json_encode($users);
    } else {
        echo json_encode(array('error' => 'Error fetching users: ' . $conn->error));
    }

    // Close connection
    $conn->close();
} else {
    echo json_encode(array('error' => 'Invalid request method.'));
}
?>
