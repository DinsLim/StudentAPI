<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once __DIR__ . '/db.php';
global $connection;

$message = '';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST["id"]) && !empty(trim($_POST["id"]))){
        $sql = "DELETE FROM student WHERE Id = ?";
        
        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            $param_id = trim($_POST["id"]);
            
            if(mysqli_stmt_execute($stmt)){
                $message = "<div class='alert alert-success'>Student record deleted successfully.</div>";
            } else{
                $message = "<div class='alert alert-danger'>Error: " . mysqli_error($connection) . "</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Error preparing statement: " . mysqli_error($connection) . "</div>";
        }
         
        mysqli_stmt_close($stmt);
    } else {
        $message = "<div class='alert alert-danger'>Invalid request. Please select a student to delete.</div>";
    }
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Student</title>
    <link rel="stylesheet" href="styles/common.css">
    <style>
        .wrapper {
            width: 600px;
            margin: 0 auto;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Delete Student</h2>
        <?php 
        if (!empty($message)) {
            echo $message;
        }
        
        if(empty($message) && isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="alert alert-danger">
                    <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                    <p>Are you sure you want to delete this student record?</p>
                    <p>
                        <input type="submit" value="Yes" class="btn btn-danger">
                        <a href="students.php" class="btn btn-secondary">No</a>
                    </p>
                </div>
            </form>
        <?php 
        } elseif (empty($message)) {
            echo "<p>Invalid request. Please select a student to delete.</p>";
        }
        ?>
        <p><a href="students.php" class="btn btn-primary">Back to Student List</a></p>
    </div>
</body>
</html>

