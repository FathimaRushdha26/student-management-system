
# Student Management System

A web-based Student Management System developed using PHP, MySQL, HTML, and CSS.

This project helps manage student records through a simple and modern admin dashboard.

## Features

- Add new student records
- View all registered students
- Edit student details
- Delete student records
- Search students by name, email, or course
- Dashboard statistics
- Responsive user interface
- Form validation
- MySQL database integration

## Technologies Used

- PHP
- MySQL
- HTML5
- CSS3
- XAMPP
- Visual Studio Code

## Database Structure

Database name: `student_management`

Table name: `students`

| Column | Description |
|---|---|
| id | Student ID (Primary Key) |
| full_name | Student's full name |
| email | Student's email (Unique) |
| phone | Contact number |
| course | Course name |
| created_at | Record creation date |

## Installation and Setup

1. Install XAMPP.
2. Start Apache and MySQL.
3. Copy this project folder into `C:\xampp\htdocs`.
4. Open phpMyAdmin at `http://localhost/phpmyadmin`.
5. Create a database named `student_management`.
6. Create the `students` table using the structure described above.
7. Update the database credentials in `config/database.php` if necessary.
8. Open the project in your browser:

   `http://localhost/student-management-system/`

## Project Structure

```text
student-management-system/
│
├── config/
│   └── database.php
│
├── css/
│   └── style.css
│
├── index.php
├── add_student.php
├── edit_student.php
├── delete_student.php
├── .gitignore
└── README.md
```

## Author

**M.I.F. Rushdha**

B.Tech in Software Technology Undergraduate  
University of Vocational Technology, Sri Lanka
