<?php
// Include database configuration file
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic form validation
    if (empty($name) || empty($email) || empty($password)) {
        echo "Please fill in all fields.";
    } else {
        // Prepare SQL statement to check if the email already exists
        $checkEmailSql = "SELECT id FROM users WHERE email = ?";

        if ($checkStmt = $conn->prepare($checkEmailSql)) {
            // Bind parameters
            $checkStmt->bind_param("s", $email);

            // Execute statement
            if ($checkStmt->execute()) {
                // Store result
                $checkStmt->store_result();

                // Check if the email already exists
                if ($checkStmt->num_rows > 0) {
                    echo "An account with this email already exists.";
                } else {
                    // Hash the password
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Prepare SQL statement for user insertion
                    $insertSql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

                    // Prepare statement
                    if ($stmt = $conn->prepare($insertSql)) {
                        // Bind parameters
                        $stmt->bind_param("sss", $name, $email, $hashed_password);

                        // Execute statement
                        if ($stmt->execute()) {
                            echo "Registration successful!";
                            header("Location: Login.html");
                            exit();
                        } else {
                            echo "Error: " . $stmt->error;
                        }

                        // Close statement
                        $stmt->close();
                    } else {
                        echo "Error preparing statement: " . $conn->error;
                    }
                }
            } else {
                echo "Error executing check email statement: " . $checkStmt->error;
            }

            // Close statement
            $checkStmt->close();
        } else {
            echo "Error preparing check email statement: " . $conn->error;
        }

        // Close connection
        $conn->close();
    }
}
?>
