# Student Management API

## Project Description

Student Management API is a system designed to manage student records in a small educational institution. The system implements API functionalities that allow users to create, retrieve, update, and delete student data. It also includes additional features like searching, filtering, authentication, and course management.

## Features

1. **Authentication**: All API requests require an X-API-Key header with the value 'it-311' for access.
2. **Student Management**: Create, read, update, and delete student records.
3. **Enhanced Search Functionality**: Search for students by name or email.
4. **Course Filtering**: Filter students by course.
5. **Course Management**: Add new courses to the system.

## Setup Instructions

### i. How to import the .sql file

1. Open phpMyAdmin.
2. To create a Database, click "New" located on the left.
3. Click "Import".
4. Open the student_management.sql file.
5. Copy all of the SQL queries.
6. Paste all of the SQL queries in the import section.
7. You have successfully imported the .sql file.

### ii. How to configure the database connection in your code

1. Gather database details (host, username, password, database name).
2. Create a reusable configuration file (e.g., `config/db.php`) for the connection.
3. Enter your database details in this file.
4. Save the file.

### iii. How to start the server

1. Ensure your web server (e.g., Apache) is running.
2. Place the project files in your web server's document root.
3. Access the API through the appropriate URL (e.g., `http://localhost/StudentAPI/index.php`).

## Using the API

1. Open a tool like Postman or use cURL for API requests.
2. Set the `X-API-Key` header to 'it-311' for all requests.
3. Use the appropriate HTTP method (GET, POST, PUT, DELETE) for each operation.
4. Enter the URL of your file (e.g., `http://localhost/StudentAPI/index.php`).
5. Add query parameters as needed (e.g., `?id=1` for specific student, `?search=john` for search).
6. Send the request to interact with the API.

## API Endpoints

- GET `/`: Retrieve all students
- GET `/?id=X`: Retrieve a specific student
- POST `/`: Create a new student
- PUT `/?id=X`: Update a specific student
- DELETE `/?id=X`: Delete a specific student
- GET `/?search=query`: Search for students by name or email
- GET `/?CourseId=X`: Filter students by course
- POST `/?action=addCourse`: Add a new course

## Adding a New Course

To add a new course using Postman:

1. Set the HTTP method to POST.
2. Use the URL: `http://localhost/StudentAPI/index.php?action=addCourse`
3. In the Headers tab, add:
   - Key: `Content-Type`  Value: `application/json`
   - Key: `X-API-KEY`     Value: `it-311`
4. In the Body tab:
   - Select "raw"
   - Choose "JSON" from the dropdown menu
   - Enter the following JSON:
     ```json
     {
         "CourseName": "Your New Course Name"
     }
