<?php
include "config/db.php";

header("Content-Type: application/json");
$requestMethod = $_SERVER["REQUEST_METHOD"];


$request = isset($_GET['request']) ? explode("/", trim($_GET['request'], "/")) : [];
$studentId = isset($_GET["id"]) ? trim($_GET["id"], "/") : null;

switch($requestMethod) {
    case 'POST':
        createStudent();
        break;
    case 'GET':
        if ($studentId) {
            getStudent($studentId);
        } elseif (isset($_GET['course'])) {
            filterStudentsByCourse($_GET['course']);
        } elseif (isset($_GET['search'])) {
            searchStudents($_GET['search']);
        } else {
            getStudents();
        }
        break;
    case 'PUT':
    case 'PATCH':
        updateStudent($studentId);
        break;
    case 'DELETE':
        if ($studentId) {
            deleteStudent($studentId);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Student ID is required for deletion"]);
        }
        break;
    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}

mysqli_close($connection);

function createStudent() {
    global $connection;
    $data = json_decode(file_get_contents("php://input"), true);
    
    $firstName = mysqli_real_escape_string($connection, $data['FirstName']);
    $lastName = mysqli_real_escape_string($connection, $data['LastName']);
    $email = mysqli_real_escape_string($connection, $data['Email']);
    $birthdate = mysqli_real_escape_string($connection, $data['Birthdate']);
    $course = mysqli_real_escape_string($connection, $data['Course']);

    if(!empty($firstName) && !empty($lastName) && !empty($email) && !empty($birthdate) && !empty($course)) {
        // Check for duplicates
        $checkDuplicate = "SELECT * FROM student WHERE FirstName = '$firstName' AND LastName = '$lastName' OR Email = '$email'";
        $result = mysqli_query($connection, $checkDuplicate);
        
        if(mysqli_num_rows($result) > 0) {
            http_response_code(409);
            echo json_encode(["message" => "A student with this name or email already exists"]);
            return;
        }
        
        $sql = "INSERT INTO student (FirstName, LastName, Email, Birthdate, Course) VALUES ('$firstName', '$lastName', '$email', '$birthdate', '$course')";
        
        if(mysqli_query($connection, $sql)) {
            http_response_code(201);
            echo json_encode(["message" => "Student created successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error creating student"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "All fields are required"]);
    }
}

function getStudents() {
    global $connection;
    $sql = "SELECT * FROM student";
    $result = mysqli_query($connection, $sql);
    $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
    echo json_encode($students);
}

function getStudent($id) {
    global $connection;
    $id = mysqli_real_escape_string($connection, $id);
    $sql = "SELECT * FROM student WHERE Id = $id";
    $result = mysqli_query($connection, $sql);
    
    if($row = mysqli_fetch_assoc($result)) {
        echo json_encode(["message" => "Student Found!"]);
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(["message" => "Student not found"]);
    }
}

function updateStudent($id) {
    global $connection;
    $data = json_decode(file_get_contents("php://input"), true);
    
    $updates = [];
    foreach(['FirstName', 'LastName', 'Email', 'Birthdate', 'Course'] as $field) {
        if(isset($data[$field])) {
            $value = mysqli_real_escape_string($connection, $data[$field]);
            $updates[] = "$field = '$value'";
        }
    }
    
    if(!empty($updates)) {
        // Check for duplicates
        if(isset($data['FirstName']) && isset($data['LastName']) && isset($data['Email'])) {
            $checkDuplicate = "SELECT * FROM student WHERE (FirstName = '{$data['FirstName']}' AND LastName = '{$data['LastName']}' OR Email = '{$data['Email']}') AND Id != $id";
            $result = mysqli_query($connection, $checkDuplicate);
            
            if(mysqli_num_rows($result) > 0) {
                http_response_code(409);
                echo json_encode(["message" => "A student with this name or email already exists"]);
                return;
            }
        }
        
        $sql = "UPDATE student SET " . implode(", ", $updates) . " WHERE Id = $id";
        
        if(mysqli_query($connection, $sql)) {
            echo json_encode(["message" => "Student updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error updating student"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["message" => "No fields to update"]);
    }
}

function deleteStudent($id) {
    global $connection;
    $id = mysqli_real_escape_string($connection, $id);

    // First, check if the student exists
    $checkSql = "SELECT Id FROM student WHERE Id = $id";
    $checkResult = mysqli_query($connection, $checkSql);

    if (!$checkResult) {
        http_response_code(500);
        echo json_encode(["message" => "Error checking student existence"]);
        return;
    }

    if (mysqli_num_rows($checkResult) == 0) {
        http_response_code(404);
        echo json_encode(["message" => "Student not found"]);
        return;
    }

    // If student exists, proceed with deletion
    $deleteSql = "DELETE FROM student WHERE Id = $id";
    
    if (mysqli_query($connection, $deleteSql)) {
        echo json_encode(["message" => "Student deleted successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error deleting student"]);
    }
}

function filterStudentsByCourse($course) {
    global $connection;
    $course = mysqli_real_escape_string($connection, $course);
    $sql = "SELECT * FROM student WHERE Course = '$course'";
    $result = mysqli_query($connection, $sql);
    
    if($result) {
        $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if(empty($students)) {
            http_response_code(404);
            echo json_encode(["message" => "No students found for the specified course"]);
        } else {
            $count = count($students);
            if ($count == 1) {
                echo json_encode(["message" => "1 student found", "data" => $students[0]]);
            } else {
                echo json_encode(["message" => "$count students found", "data" => $students]);
            }
        }
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error filtering students by course"]);
    }
}

function searchStudents($query) {
    global $connection;
    $query = mysqli_real_escape_string($connection, $query);
    $sql = "SELECT * FROM student WHERE FirstName LIKE '%$query%' OR LastName LIKE '%$query%' OR Email LIKE '%$query%'";
    $result = mysqli_query($connection, $sql);
    
    if($result) {
        $students = mysqli_fetch_all($result, MYSQLI_ASSOC);
        if(empty($students)) {
            http_response_code(404);
            echo json_encode(["message" => "No students found matching the search query"]);
        } else {
            $count = count($students);
            if ($count == 1) {
                echo json_encode(["message" => "1 student found", "data" => $students[0]]);
            } else {
                echo json_encode(["message" => "$count students found", "data" => $students]);
            }
        }
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Error searching for students"]);
    }
}