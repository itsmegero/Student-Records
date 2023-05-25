<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if the student ID is provided in the URL
if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = $_GET['id'];

$mysqli = new mysqli('localhost', 'root', '', 'StudentRecords');
if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

$sql = 'DELETE FROM students WHERE id = ?';
$stmt = $mysqli->prepare($sql);
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    header('Location: index.php');
    exit;
} else {
    $deleteError = 'Error deleting student record: ' . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Student Record</title>
</head>
<body>
    <h1>Delete Student Record</h1>

    <?php
    if (isset($deleteError)) {
        echo '<p>' . $deleteError . '</p>';
    }
    ?>

    <p>Are you sure you want to delete this student record?</p>
    <form method="post" action="">
        <input type="submit" value="Delete">
    </form>
</body>
</html>
