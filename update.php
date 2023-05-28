<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['studentId'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $course = $_POST['course'];
    $yearLevel = $_POST['yearLevel'];
    $email = $_POST['email'];
    $mysqli = new mysqli('localhost', 'root', '', 'StudentRecords');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare('UPDATE students SET name = ?, age = ?, Course = ?, Year_Level = ?, email = ? WHERE id = ?');
    $stmt->bind_param('sisssi', $name, $age, $course, $yearLevel, $email, $studentId);
    
    if ($stmt->execute()) {
        $stmt->close();
        $mysqli->close();
        header('Location: index.php');
        exit;
    } else {
        $stmt->close();
        $mysqli->close();
        die('Update Failed');
    }

} else {
    header('Location: index.php');
    exit;
}

?>
