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
    <style>
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .search-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-container input,
        .search-container select {
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }
        .search-container input {
            flex-grow: 1;
        }
        .table-container {
            overflow-x: auto;
        }
        #courseTable {
            min-width: 100%;
        }
        #courseTable th {
            white-space: nowrap;
        }
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .pagination button {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="dashboard-header">
                <h1>Course Management</h1>
                <a href="dashboard.php" class="btn btn-primary">Back to Dashboard</a>
            </div>

            <div class="search-container">
                <input type="text" id="searchInput" placeholder="Search course name or description">
                <a href="add_course.php" class="btn btn-primary">Add New Course</a>
            </div>

            <div class="table-container">
                <table id="courseTable">
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

            <div class="pagination">
                <button id="prevPage" class="btn btn-primary">&laquo; Previous</button>
                <button id="nextPage" class="btn btn-primary">Next &raquo;</button>
            </div>
        </div>
    </div>

    <script>
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('courseTable');
        const rows = table.getElementsByTagName('tr');
        const itemsPerPage = 10;
        let currentPage = 1;

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            let visibleRows = 0;

            for (let i = 1; i < rows.length; i++) {
                const row = rows[i];
                const courseName = row.cells[1].textContent.toLowerCase();
                const courseDescription = row.cells[2].textContent.toLowerCase();

                if (courseName.includes(searchTerm) || courseDescription.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            }

            updatePagination();
            showPage(1);
        }

        function updatePagination() {
            const visibleRows = Array.from(rows).slice(1).filter(row => row.style.display !== 'none');
            const pageCount = Math.ceil(visibleRows.length / itemsPerPage);
            
            document.getElementById('prevPage').disabled = currentPage === 1;
            document.getElementById('nextPage').disabled = currentPage === pageCount || pageCount === 0;
        }

        function showPage(page) {
            const visibleRows = Array.from(rows).slice(1).filter(row => row.style.display !== 'none');
            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;

            visibleRows.forEach((row, index) => {
                if (index >= startIndex && index < endIndex) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            currentPage = page;
            updatePagination();
        }

        searchInput.addEventListener('input', filterTable);

        document.getElementById('prevPage').addEventListener('click', () => showPage(currentPage - 1));
        document.getElementById('nextPage').addEventListener('click', () => showPage(currentPage + 1));

        // Initial setup
        filterTable();
    </script>
</body>
</html>

