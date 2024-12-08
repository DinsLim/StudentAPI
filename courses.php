<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/StudentAPI/db.php';
global $connection;

// Initialize variables
$search = "";
$sql = "SELECT * FROM courses";

// Search functionality
if(isset($_GET['search'])) {
    $search = trim($_GET['search']);
    $sql .= " WHERE CourseName LIKE '%$search%' OR CourseDescription LIKE '%$search%'";
}

$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management</title>
    <link rel="stylesheet" href="styles/common.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Course Management</h1>

            <a href="dashboard.php" class="nav-link">Back to Dashboard</a>

            <div class="search-container">
                <form action="" method="GET">
                    <input type="text" name="search" placeholder="Search course name or description" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit">Search</button>
                </form>
            </div>

            <a href="add_course.php" class="btn btn-primary">Add New Course</a>

            <table>
                <thead>
                    <tr>
                        <th>Course ID</th>
                        <th>Course Name</th>
                        <th>Course Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>" . $row['Id'] . "</td>";
                            echo "<td>" . $row['CourseName'] . "</td>";
                            echo "<td>" . $row['CourseDescription'] . "</td>";
                            echo "<td class='actions'>";
                            echo "<a href='edit_course.php?id=" . $row['Id'] . "' class='btn btn-primary'>Edit</a>";
                            echo "<a href='delete_course.php?id=" . $row['Id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No courses found. <a href='add_course.php'>Add a new course</a>.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

