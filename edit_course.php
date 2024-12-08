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

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    
    $input_course_name = trim($_POST["course_name"]);
    if(empty($input_course_name)){
        $course_name_err = "Please enter a course name.";
    } else{
        $course_name = $input_course_name;
    }
    
    $input_course_description = trim($_POST["course_description"]);
    if(empty($input_course_description)){
        $course_description_err = "Please enter a course description.";
    } else{
        $course_description = $input_course_description;
    }
    
    if(empty($course_name_err) && empty($course_description_err)){
        $sql = "UPDATE courses SET CourseName=?, CourseDescription=? WHERE Id=?";
         
        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, "ssi", $param_course_name, $param_course_description, $param_id);
            
            $param_course_name = $course_name;
            $param_course_description = $course_description;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: courses.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
    }
    
    mysqli_close($connection);
} else{
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        $id =  trim($_GET["id"]);
        
        $sql = "SELECT * FROM courses WHERE Id = ?";
        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
        
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $course_name = $row["CourseName"];
                    $course_description = $row["CourseDescription"];
                } else{
                    header("location: error.php");
                    exit();
                }
                
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        mysqli_stmt_close($stmt);
        
        mysqli_close($connection);
    } else{
        header("location: error.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Course</title>
    <link rel="stylesheet" href="styles/common.css">
</head>
<body>
    <div class="container">
        <h2>Update Course</h2>
        <p>Please edit the input values and submit to update the course.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Course Name</label>
                <input type="text" name="course_name" class="form-control <?php echo (!empty($course_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $course_name; ?>">
                <span class="invalid-feedback"><?php echo $course_name_err;?></span>
            </div>
            <div class="form-group">
                <label>Course Description</label>
                <textarea name="course_description" class="form-control <?php echo (!empty($course_description_err)) ? 'is-invalid' : ''; ?>"><?php echo $course_description; ?></textarea>
                <span class="invalid-feedback"><?php echo $course_description_err;?></span>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="courses.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

