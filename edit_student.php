<?php
require_once "config/database.php";

if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    die("Invalid student ID.");
}

$id = (int) $_GET["id"];
$message = "";
$messageType = "";

// Get existing student
$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$student = $result->fetch_assoc();
$stmt->close();

if (!$student) {
    die("Student not found.");
}

// Update student
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = trim($_POST["full_name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $course = trim($_POST["course"]);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $messageType = "error";
    } else {

        $stmt = $conn->prepare(
            "UPDATE students
             SET full_name = ?, email = ?, phone = ?, course = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $full_name,
            $email,
            $phone,
            $course,
            $id
        );

        if ($stmt->execute()) {

            $message = "Student updated successfully!";
            $messageType = "success";

            $student["full_name"] = $full_name;
            $student["email"] = $email;
            $student["phone"] = $phone;
            $student["course"] = $course;

        } else {

            if ($stmt->errno == 1062) {
                $message = "This email is already registered.";
            } else {
                $message = "Unable to update student.";
            }

            $messageType = "error";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Student | StudentHub</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <h2>Student<span>Hub</span></h2>
            <p>Management System</p>
        </div>

        <nav>
            <a href="index.php" class="nav-link">
                Dashboard
            </a>

            <a href="add_student.php" class="nav-link">
                Add Student
            </a>
        </nav>

        <div class="sidebar-footer">
            <p>StudentHub v1.0</p>
        </div>

    </aside>


    <main class="main-content">

        <header class="top-header">

            <div>
                <h1>Edit Student</h1>
                <p>Update student information</p>
            </div>

            <a href="index.php" class="reset-btn">
                ← Back to Dashboard
            </a>

        </header>


        <section class="form-section">

            <div class="form-card">

                <h2>Update Student Information</h2>

                <p class="form-description">
                    Student ID: #<?php echo $id; ?>
                </p>

                <?php if ($message != ""): ?>

                    <div class="alert <?php echo $messageType; ?>">
                        <?php echo htmlspecialchars($message); ?>
                    </div>

                <?php endif; ?>


                <form method="POST" class="student-form">

                    <div class="form-group">

                        <label>Full Name *</label>

                        <input
                            type="text"
                            name="full_name"
                            value="<?php echo htmlspecialchars($student["full_name"]); ?>"
                            required
                            maxlength="100"
                        >

                    </div>


                    <div class="form-group">

                        <label>Email Address *</label>

                        <input
                            type="email"
                            name="email"
                            value="<?php echo htmlspecialchars($student["email"]); ?>"
                            required
                            maxlength="100"
                        >

                    </div>


                    <div class="form-group">

                        <label>Phone Number</label>

                        <input
                            type="tel"
                            name="phone"
                            value="<?php echo htmlspecialchars($student["phone"] ?? ""); ?>"
                            maxlength="15"
                        >

                    </div>


                    <div class="form-group">

                        <label>Course</label>

                        <input
                            type="text"
                            name="course"
                            value="<?php echo htmlspecialchars($student["course"] ?? ""); ?>"
                            maxlength="100"
                        >

                    </div>


                    <div class="form-actions">

                        <button type="submit" class="add-btn">
                            Update Student
                        </button>

                        <a href="index.php" class="reset-btn">
                            Cancel
                        </a>

                    </div>

                </form>

            </div>

        </section>

    </main>

</div>

</body>
</html>
