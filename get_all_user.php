<?php
// Include database configuration file
require 'config.php';
session_start();

// Assuming the token is stored in a session variable
$token = $_SESSION['token'] ?? '';

if (empty($token)) {
    echo json_encode(array('error' => 'You must be logged in to view users.'));
    exit();
}

// Fetch user_id and role from the tokens and users tables using the token
$sql = "SELECT users.id, users.role 
        FROM tokens 
        INNER JOIN users ON tokens.user_id = users.id 
        WHERE tokens.token = ?";

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
$sqlUsers = "SELECT name, email, created_at FROM users";

// Prepare and execute the SQL statement
if ($stmtUsers = $conn->prepare($sqlUsers)) {
    $stmtUsers->execute();
    $resultUsers = $stmtUsers->get_result();
    $users = array();
    while ($row = $resultUsers->fetch_assoc()) {
        $users[] = $row;
    }
    $stmtUsers->close();
   // echo json_encode($users);
} else {
    echo json_encode(array('error' => 'Error fetching users: ' . $conn->error));
}

// Close connection
$conn->close();
?>
