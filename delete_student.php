
<?php

require_once "config/database.php";

// Check student ID
if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    die("Invalid student ID.");
}

$id = (int) $_GET["id"];

// Delete student using prepared statement
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();
    header("Location: index.php");
    exit;
} else {
    echo "Error deleting student: " . htmlspecialchars($stmt->error);
}

$stmt->close();

?>
