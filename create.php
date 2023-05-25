<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process the form data
    $name = $_POST['name'];
    $age = $_POST['age'];
    $Course = $_POST['Course'];
    $Year_Level = $_POST['Year_Level'];
    $email = $_POST['email'];

    $mysqli = new mysqli('localhost', 'root', '', 'StudentRecords');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $sql = 'INSERT INTO students (name, age, Course, Year_Level, email) VALUES (?, ?, ?, ?, ?)';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sisss', $name, $age, $Course, $Year_Level, $email);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $addError = 'Error adding student record: ' . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Student</title>
</head>
<body>
    <h1>Add New Student</h1>

    <?php
    if (isset($addError)) {
        echo '<p>' . $addError . '</p>';
    }
    ?>

    <form method="post" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>

        <label for="age">Age:</label>
        <input type="number" id="age" name="age" required><br>

        <label for="Course">Course:</label>
        <input type="text" id="Course" name="Course" required><br>

        <label for="Year_Level">Year Level:</label>
        <input type="number" id="Year_Level" name="Year_Level" required><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <input type="submit" value="Add Student">
    </form>
</body>
</html>
