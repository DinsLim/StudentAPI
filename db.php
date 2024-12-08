<?php
//db.php
$host='localhost';
$db_name='studentcrud';
$username='root';
$password='';
$connection = mysqli_connect($host, $username, $password, $db_name);

if(!$connection) {
    die("Connection Failed: " . mysqli_connect_error());
}

// Set charset to ensure proper encoding of special characters
mysqli_set_charset($connection, "utf8mb4");

// Error handling function
function handle_sql_errors($query) {
    global $connection;
    echo "Error: " . mysqli_error($connection) . "<br>";
    echo "Query: " . $query;
    exit();
}
?>

