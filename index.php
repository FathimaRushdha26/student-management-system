
<?php
require_once "config/database.php";

// Get total student count
$countQuery = "SELECT COUNT(*) AS total FROM students";
$countResult = $conn->query($countQuery);
$totalStudents = $countResult->fetch_assoc()["total"];

// Count unique courses
$courseQuery = "
    SELECT COUNT(DISTINCT course) AS total
    FROM students
    WHERE course IS NOT NULL AND course != ''
";

$courseResult = $conn->query($courseQuery);
$totalCourses = $courseResult->fetch_assoc()["total"];

// Get latest student
$latestQuery = "
    SELECT full_name
    FROM students
    ORDER BY id DESC
    LIMIT 1
";

$latestResult = $conn->query($latestQuery);
$latestStudent = $latestResult->fetch_assoc();

$latestStudentName = $latestStudent
    ? $latestStudent["full_name"]
    : "No students yet";



// Search functionality
$search = trim($_GET["search"] ?? "");

if ($search !== "") {

    $searchTerm = "%" . $search . "%";

    $stmt = $conn->prepare(
        "SELECT * FROM students
         WHERE full_name LIKE ?
         OR email LIKE ?
         OR course LIKE ?
         ORDER BY id DESC"
    );

    $stmt->bind_param(
        "sss",
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    $stmt->execute();
    $result = $stmt->get_result();

} else {

    // Display all students
    $sql = "SELECT * FROM students ORDER BY id DESC";
    $result = $conn->query($sql);

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Management Dashboard</title>

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
            <a href="index.php" class="nav-link active">
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


    <!-- Main Content -->
    <main class="main-content">

        <!-- Top Header -->
        <header class="top-header">

            <div>
                <h1>Dashboard</h1>
                <p>Welcome to Student Management System</p>
            </div>

            <a href="add_student.php" class="add-btn">
                + Add Student
            </a>

        </header>


        <!-- Statistics Cards -->
        
<section class="stats-container">

    <div class="stat-card">
        <div class="stat-icon">👨‍🎓</div>
        <div>
            <p>Total Students</p>
            <h2><?php echo $totalStudents; ?></h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">📚</div>
        <div>
            <p>Total Courses</p>
            <h2><?php echo $totalCourses; ?></h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">🆕</div>
        <div>
            <p>Latest Student</p>
            <h2 class="latest-name">
                <?php echo htmlspecialchars($latestStudentName); ?>
            </h2>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon">✅</div>
        <div>
            <p>System Status</p>
            <h2>Active</h2>
        </div>
    </div>

</section>



        <!-- Student Table -->
        <section class="table-section">

           
<div class="table-header">

    <div>
        <h2>Student Records</h2>
        <p>Manage all registered students</p>
    </div>

    <form method="GET" action="index.php" class="search-form">

        <input
            type="text"
            name="search"
            placeholder="Search name, email or course..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">Search</button>

        <a href="index.php" class="reset-btn">Reset</a>

    </form>

</div>



            <div class="table-wrapper">

                <table>

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Student Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if ($result && $result->num_rows > 0): ?>

                        <?php while ($row = $result->fetch_assoc()): ?>

                            <tr>

                                <td>
                                    #<?php echo (int)$row["id"]; ?>
                                </td>

                                <td>
                                    <strong>
                                        <?php echo htmlspecialchars($row["full_name"]); ?>
                                    </strong>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row["email"]); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row["phone"] ?? ""); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($row["course"] ?? ""); ?>
                                </td>

                                <td class="actions">

                                    <a class="edit-btn"
                                       href="edit_student.php?id=<?php echo (int)$row['id']; ?>">
                                        Edit
                                    </a>

                                    <a class="delete-btn"
                                       href="delete_student.php?id=<?php echo (int)$row['id']; ?>"
                                       onclick="return confirm('Are you sure you want to delete this student?');">
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="empty-message">
                                No students found.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </section>

        <footer>
            <p>© 2026 StudentHub. All rights reserved.</p>
        </footer>

    </main>

</div>

</body>
</html>
