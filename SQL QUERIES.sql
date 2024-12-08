CREATE TABLE student (
    Id INT(100) NOT NULL AUTO_INCREMENT,
    FirstName VARCHAR(500) NOT NULL,
    LastName VARCHAR(500) NOT NULL,
    Email VARCHAR(500) NOT NULL,
    Birthdate INT(100) NOT NULL,
    Course VARCHAR(500) NOT NULL,
    PRIMARY KEY (Id)
);

CREATE TABLE courses (
    Id INT(100) NOT NULL AUTO_INCREMENT,
    CourseName VARCHAR(500) NOT NULL,
    CourseDescription VARCHAR(500) NOT NULL,
    PRIMARY KEY (Id)
);

-- Create the users table
CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert a sample user (password is 'password123')
INSERT INTO users (username, password) VALUES ('admin', '$2y$10$8IjGzXbzZ5X5Kl5z7z5z5O5z5z5z5z5z5z5z5z5z5z5z5z5z5z5z5');
