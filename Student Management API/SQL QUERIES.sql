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
