<?php
session_start();

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/StudentAPI/db.php';
global $connection;

// Initialize variables
$search = $filter = "";
$sql = "SELECT * FROM student WHERE 1=1";

// Search functionality
if(isset($_GET['search']) && !empty($_GET['search'])) {
    $search = trim($_GET['search']);
    $sql .= " AND (FirstName LIKE '%$search%' OR LastName LIKE '%$search%' OR Email LIKE '%$search%')";
}

// Filter functionality
if(isset($_GET['filter']) && !empty($_GET['filter'])) {
    $filter = trim($_GET['filter']);
    $sql .= " AND Course = '$filter'";
}

$result = mysqli_query($connection, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - Student List</title>
    <link rel="stylesheet" href="styles/common.css">
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Student Management System - Student List</h1>

            <a href="dashboard.php" class="nav-link">Back to Dashboard</a>

            <div class="search-container">
                <form action="" method="GET">
                    <input type="text" name="search" placeholder="Search name or email" value="<?php echo htmlspecialchars($search); ?>">
                    <select name="filter">
                        <option value="">All Courses</option>
                        <?php
                        $courses = mysqli_query($connection, "SELECT DISTINCT Course FROM student");
                        while($course = mysqli_fetch_assoc($courses)) {
                            $selected = ($filter == $course['Course']) ? 'selected' : '';
                            echo "<option value='" . $course['Course'] . "' $selected>" . $course['Course'] . "</option>";
                        }
                        ?>
                    </select>
                    <button type="submit">Search</button>
                </form>
            </div>

            <a href="add_student.php" class="btn btn-primary">Add New Student</a>

            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Birthdate</th>
                        <th>Course</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(mysqli_num_rows($result) > 0){
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>" . $row['Id'] . "</td>";
                            echo "<td>" . $row['FirstName'] . "</td>";
                            echo "<td>" . $row['LastName'] . "</td>";
                            echo "<td>" . $row['Email'] . "</td>";
                            echo "<td>" . date('Y-m-d', $row['Birthdate']) . "</td>";
                            echo "<td>" . $row['Course'] . "</td>";
                            echo "<td class='actions'>";
                            echo "<a href='edit_student.php?id=" . $row['Id'] . "' class='btn btn-primary'>Edit</a>";
                            echo "<a href='delete_student.php?id=" . $row['Id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure?\")'>Delete</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='7'>No students found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Client-side filtering (optional, for better user experience)
        document.querySelector('select[name="filter"]').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</body>
</html>

