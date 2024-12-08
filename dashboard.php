<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - Dashboard</title>
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --background-color: #ecf0f1;
            --text-color: #333;
            --border-color: #bdc3c7;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--background-color);
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
        }

        h1 {
            text-align: center;
            color: var(--secondary-color);
            margin-bottom: 30px;
        }

        .dashboard-menu {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .dashboard-item {
            background-color: var(--primary-color);
            color: #fff;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease;
            width: 200px;
        }

        .dashboard-item:hover {
            background-color: #2980b9;
        }

        .dashboard-item h2 {
            margin: 0;
            font-size: 24px;
        }

        .dashboard-item p {
            margin: 10px 0 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Student Management System Dashboard</h1>

        <div class="dashboard-menu">
            <a href="index.php" class="dashboard-item">
                <h2>Students</h2>
                <p>Manage student information</p>
            </a>
            <a href="Course.php" class="dashboard-item">
                <h2>Courses</h2>
                <p>Manage course information</p>
            </a>
        </div>
    </div>
</body>
</html>
