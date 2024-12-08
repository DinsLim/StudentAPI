<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management</title>
    <style>
        body {
            background-color: #b8a7a7; 
            color: #fff; 
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #000; 
            color: #fff; 
        }

        th, td {
            border: 1px solid #fff; 
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #333; 
        }

        h1 {
            text-align: center;
        }

        .search-container {
            text-align: right;
            margin: 20px 0;
        }

        .search-container input[type="text"] {
            padding: 10px;
            width: 300px;
            border: none;
            border-radius: 4px;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-container button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <h1>Student Management API</h1>

    <div class="search-container">
        <form action="search.php" method="GET">
            <input type="text" name="query" placeholder="Search name, email, or course" />
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Table -->
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
            <!-- Rows -->
        </tbody>
    </table>
</body>
</html>