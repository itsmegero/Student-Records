<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted form data
    $studentId = $_POST['studentId'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $course = $_POST['course'];
    $yearLevel = $_POST['yearLevel'];
    $email = $_POST['email'];

    // Perform the update operation using the provided data
    $mysqli = new mysqli('localhost', 'root', '', 'StudentRecords');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    // Prepare and execute the update statement
    $stmt = $mysqli->prepare('UPDATE students SET name = ?, age = ?, Course = ?, Year_Level = ?, email = ? WHERE id = ?');
    $stmt->bind_param('sisssi', $name, $age, $course, $yearLevel, $email, $studentId);
    
    if ($stmt->execute()) {
        // Update successful
        $stmt->close();
        $mysqli->close();
        header('Location: index.php');
        exit;
    } else {
        // Update failed, display an error message
        $stmt->close();
        $mysqli->close();
        die('Update Failed');
    }

} else {
    // Redirect if accessed directly without a POST request
    header('Location: index.php');
    exit;
}

?>
