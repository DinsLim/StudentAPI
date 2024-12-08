<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once __DIR__ . '/db.php';
global $connection;

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $sql = "DELETE FROM courses WHERE id = ?";
    
    if($stmt = mysqli_prepare($connection, $sql)){
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        $param_id = trim($_POST["id"]);
        
        if(mysqli_stmt_execute($stmt)){
            header("location: courses.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    mysqli_stmt_close($stmt);
    
    mysqli_close($connection);
} else{
    if(empty(trim($_GET["id"]))){
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Course</title>
    <link rel="stylesheet" href="styles/common.css">
</head>
<body>
    <div class="container">
        <h2>Delete Course</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="alert alert-danger">
                <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                <p>Are you sure you want to delete this course?</p>
                <p>
                    <input type="submit" value="Yes" class="btn btn-danger">
                    <a href="courses.php" class="btn btn-secondary">No</a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>

