
<?php
require_once "config/database.php";

$message = "";
$messageType = "";

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
            "INSERT INTO students (full_name, email, phone, course)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $full_name,
            $email,
            $phone,
            $course
        );

        if ($stmt->execute()) {
            $message = "Student added successfully!";
            $messageType = "success";
        } else {
            if ($stmt->errno == 1062) {
                $message = "This email address is already registered.";
            } else {
                $message = "Unable to add student. Please try again.";
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

    <title>Add Student | StudentHub</title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="dashboard">

    <!-- Sidebar -->
    <aside class="sidebar">

        <div class="logo">
            <h2>Student<span>Hub</span></h2>
            <p>Management System</p>
        </div>

        <nav>
            <a href="index.php" class="nav-link">
                Dashboard
            </a>

            <a href="add_student.php" class="nav-link active">
                Add Student
            </a>
        </nav>

        <div class="sidebar-footer">
            <p>StudentHub v1.0</p>
        </div>

    </aside>


    <!-- Main Content -->
    <main class="main-content">

        <header class="top-header">

            <div>
                <h1>Add New Student</h1>
                <p>Enter student information below</p>
            </div>

            <a href="index.php" class="reset-btn">
                ← Back to Dashboard
            </a>

        </header>


        <section class="form-section">

            <div class="form-card">

                <h2>Student Information</h2>

                <p class="form-description">
                    Please complete all required fields.
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
                            placeholder="Enter full name"
                            value="<?php echo htmlspecialchars($_POST["full_name"] ?? ""); ?>"
                            required
                            maxlength="100"
                        >

                    </div>


                    <div class="form-group">

                        <label>Email Address *</label>

                        <input
                            type="email"
                            name="email"
                            placeholder="example@email.com"
                            value="<?php echo htmlspecialchars($_POST["email"] ?? ""); ?>"
                            required
                            maxlength="100"
                        >

                    </div>


                    <div class="form-group">

                        <label>Phone Number</label>

                        <input
                            type="tel"
                            name="phone"
                            placeholder="Enter phone number"
                            value="<?php echo htmlspecialchars($_POST["phone"] ?? ""); ?>"
                            maxlength="15"
                        >

                    </div>


                    <div class="form-group">

                        <label>Course</label>

                        <input
                            type="text"
                            name="course"
                            placeholder="Enter course name"
                            value="<?php echo htmlspecialchars($_POST["course"] ?? ""); ?>"
                            maxlength="100"
                        >

                    </div>


                    <div class="form-actions">

                        <button type="submit" class="add-btn">
                            Save Student
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
