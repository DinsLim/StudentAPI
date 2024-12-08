<?php
session_start();
require_once __DIR__ . '/db.php';

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Check if username is set in the session
$username = isset($_SESSION["username"]) ? htmlspecialchars($_SESSION["username"]) : "User";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System - Dashboard</title>
    <link rel="stylesheet" href="styles/common.css">
    <style>
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
    border-radius: 8px;
    text-align: center;
    text-decoration: none;
    transition: background-color 0.3s ease;
    width: 200px;
  }

  .dashboard-item:hover {
    background-color: #2980b9;
  }

  .dashboard-item h3 {
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
        <div class="card">
            <h1>Welcome, <?php echo $username; ?>!</h1>
            <h2>Student Management System Dashboard</h2>

            <div class="dashboard-menu">
                <a href="students.php" class="dashboard-item">
                    <h3>Students</h3>
                    <p>Manage student information</p>
                </a>
                <a href="courses.php" class="dashboard-item">
                    <h3>Courses</h3>
                    <p>Manage course information</p>
                </a>
            </div>
            
            <p style="text-align: center;">
                <a href="logout.php" class="btn btn-danger">Sign Out</a>
            </p>
        </div>
    </div>
</body>
</html>

