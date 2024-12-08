<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/StudentAPI/db.php';

// Function to update password
function updatePassword($username, $newPassword) {
    global $connection;
    
    // Hash the new password
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    // Prepare an update statement
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    
    if($stmt = mysqli_prepare($connection, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $hashedPassword, $username);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            echo "Password updated successfully.";
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }
}

// Usage
$username = 'admin'; // Replace with the username you're trying to log in with
$newPassword = 'password123'; // Replace with the password you want to set

updatePassword($username, $newPassword);

mysqli_close($connection);
?>

