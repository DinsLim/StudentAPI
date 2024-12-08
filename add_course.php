<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once __DIR__ . '/db.php';
global $connection;

$course_name = $course_description = "";
$course_name_err = $course_description_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate course name
    if(empty(trim($_POST["course_name"]))){
        $course_name_err = "Please enter course name.";
    } else{
        $course_name = trim($_POST["course_name"]);
    }
    
    // Validate course description
    if(empty(trim($_POST["course_description"]))){
        $course_description_err = "Please enter course description.";
    } else{
        $course_description = trim($_POST["course_description"]);
    }
    
    // Check input errors before inserting in database
    if(empty($course_name_err) && empty($course_description_err)){
        $sql = "INSERT INTO courses (CourseName, CourseDescription) VALUES (?, ?)";
         
        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $param_course_name, $param_course_description);
            
            $param_course_name = $course_name;
            $param_course_description = $course_description;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: courses.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    mysqli_close($connection);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Course</title>
    <link rel="stylesheet" href="styles/common.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Add Course</h2>
            <p>Please fill this form to add a new course.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Course Name</label>
                    <input type="text" name="course_name" value="<?php echo $course_name; ?>">
                    <span class="invalid-feedback"><?php echo $course_name_err; ?></span>
                </div>    
                <div class="form-group">
                    <label>Course Description</label>
                    <textarea name="course_description" rows="4"><?php echo $course_description; ?></textarea>
                    <span class="invalid-feedback"><?php echo $course_description_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="courses.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>    
</body>
</html>

