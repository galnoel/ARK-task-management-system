<?php
// Include database configuration file
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Basic form validation
    if (empty($email) || empty($password)) {
        echo "Please fill in all fields.";
    } else {
        // Prepare SQL statement to select the user with the provided email
        $sql = "SELECT id, password FROM users WHERE email = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            // Bind parameters
            $stmt->bind_param("s", $email);
            
            // Execute statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();
                
                // Debugging: Output number of rows found
                echo "Number of rows found: " . $stmt->num_rows . "<br>";
                
                // Check if the user exists
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $hashed_password);
                    
                    if ($stmt->fetch()) {
                        // Verify the password
                        if (password_verify($password, $hashed_password)) {
                            // Generate a token
                            $token = bin2hex(random_bytes(16));
                            
                            // Store the token in the database with the user id
                            $sql = "INSERT INTO tokens (user_id, token) VALUES (?, ?)";
                            
                            if ($stmt = $conn->prepare($sql)) {
                                // Bind parameters
                                $stmt->bind_param("is", $id, $token);
                                
                                // Execute statement
                                if ($stmt->execute()) {
                                    // Redirect to Teams page
                                    header("Location: Teams.html");
                                    exit();
                                } else {
                                    echo "Error storing token: " . $stmt->error;
                                }
                            } else {
                                echo "Error preparing token statement: " . $conn->error;
                            }
                        } else {
                            echo "Invalid password.";
                        }
                    }
                } else {
                    echo "No account found with that email.";
                }
            } else {
                echo "Error executing statement: " . $stmt->error;
            }
            
            // Close statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
        
        // Close connection
        $conn->close();
    }
}
?>