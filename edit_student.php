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

if(isset($_POST["id"]) && !empty($_POST["id"])){
    $id = $_POST["id"];
    
    $input_first_name = trim($_POST["first_name"]);
    if(empty($input_first_name)){
        $first_name_err = "Please enter a first name.";
    } else{
        $first_name = $input_first_name;
    }
    
    $input_last_name = trim($_POST["last_name"]);
    if(empty($input_last_name)){
        $last_name_err = "Please enter a last name.";
    } else{
        $last_name = $input_last_name;
    }
    
    $input_email = trim($_POST["email"]);
    if(empty($input_email)){
        $email_err = "Please enter an email.";
    } else{
        $email = $input_email;
    }
    
    $input_birthdate = trim($_POST["birthdate"]);
    if(empty($input_birthdate)){
        $birthdate_err = "Please enter a birthdate.";
    } else{
        $birthdate = strtotime($input_birthdate);
    }
    
    $input_course = trim($_POST["course"]);
    if(empty($input_course)){
        $course_err = "Please enter a course.";
    } else{
        $course = $input_course;
    }
    
    if(empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($birthdate_err) && empty($course_err)){
        $sql = "UPDATE student SET FirstName=?, LastName=?, Email=?, Birthdate=?, Course=? WHERE Id=?";
         
        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, "sssisi", $param_first_name, $param_last_name, $param_email, $param_birthdate, $param_course, $param_id);
            
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;
            $param_birthdate = $birthdate;
            $param_course = $course;
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                header("location: students.php");
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
        
        $sql = "SELECT * FROM student WHERE Id = ?";
        if($stmt = mysqli_prepare($connection, $sql)){
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            $param_id = $id;
            
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
        
                if(mysqli_num_rows($result) == 1){
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    $first_name = $row["FirstName"];
                    $last_name = $row["LastName"];
                    $email = $row["Email"];
                    $birthdate = $row["Birthdate"];
                    $course = $row["Course"];
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
    <title>Update Record</title>
    <link rel="stylesheet" href="styles/common.css">
</head>
<body>
    <div class="container">
        <h2>Update Student</h2>
        <p>Please edit the input values and submit to update the student record.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                <span class="invalid-feedback"><?php echo $first_name_err;?></span>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                <span class="invalid-feedback"><?php echo $last_name_err;?></span>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="text" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err;?></span>
            </div>
            <div class="form-group">
                <label>Birthdate</label>
                <input type="date" name="birthdate" class="form-control <?php echo (!empty($birthdate_err)) ? 'is-invalid' : ''; ?>" value="<?php echo date('Y-m-d', $birthdate); ?>">
                <span class="invalid-feedback"><?php echo $birthdate_err;?></span>
            </div>
            <div class="form-group">
                <label>Course</label>
                <select name="course" class="form-control <?php echo (!empty($course_err)) ? 'is-invalid' : ''; ?>">
                    <?php
                    $sql = "SELECT CourseName FROM courses";
                    if($result = mysqli_query($connection, $sql)){
                        while($row = mysqli_fetch_array($result)){
                            $selected = ($row['CourseName'] == $course) ? 'selected' : '';
                            echo "<option value='" . $row['CourseName'] . "' " . $selected . ">" . $row['CourseName'] . "</option>";
                        }
                        mysqli_free_result($result);
                    }
                    ?>
                </select>
                <span class="invalid-feedback"><?php echo $course_err;?></span>
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>"/>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="students.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>

