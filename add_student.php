<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once __DIR__ . '/db.php';
global $connection;

$first_name = $last_name = $email = $birthdate = $course = "";
$first_name_err = $last_name_err = $email_err = $birthdate_err = $course_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter first name.";
    } else{
        $first_name = trim($_POST["first_name"]);
    }
    
    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter last name.";
    } else{
        $last_name = trim($_POST["last_name"]);
    }
    
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter email.";
    } else{
        $email = trim($_POST["email"]);
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $email_err = "Invalid email format.";
        }
    }
    
    // Validate birthdate
    if(empty(trim($_POST["birthdate"]))){
        $birthdate_err = "Please enter birthdate.";
    } else{
        $birthdate = strtotime(trim($_POST["birthdate"]));
    }
    
    // Validate course
    if(empty(trim($_POST["course"]))){
        $course_err = "Please enter course.";
    } else{
        $course = trim($_POST["course"]);
    }
    
    // Check input errors before inserting in database
    if(empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($birthdate_err) && empty($course_err)){
        $sql = "INSERT INTO student (FirstName, LastName, Email, Birthdate, Course) VALUES (?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, "sssis", $param_first_name, $param_last_name, $param_email, $param_birthdate, $param_course);
            
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;
            $param_birthdate = $birthdate;
            $param_course = $course;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: students.php");
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
    <title>Add Student</title>
    <link rel="stylesheet" href="styles/common.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h2>Add Student</h2>
            <p>Please fill this form to add a new student.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?php echo $first_name; ?>">
                    <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
                </div>    
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="<?php echo $last_name; ?>">
                    <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Birthdate</label>
                    <input type="date" name="birthdate" value="<?php echo $birthdate ? date('Y-m-d', $birthdate) : ''; ?>">
                    <span class="invalid-feedback"><?php echo $birthdate_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Course</label>
                    <select name="course">
                        <option value="">Select a course</option>
                        <?php
                        $course_query = mysqli_query($connection, "SELECT CourseName FROM courses");
                        while($course_row = mysqli_fetch_assoc($course_query)) {
                            $selected = ($course == $course_row['CourseName']) ? 'selected' : '';
                            echo "<option value='" . $course_row['CourseName'] . "' $selected>" . $course_row['CourseName'] . "</option>";
                        }
                        ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $course_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="students.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>    
</body>
</html>

