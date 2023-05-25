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

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process the form data
    $id = $_GET['id'];
    $name = $_POST['name'];
    $age = $_POST['age'];
    $Course = $_POST['Course'];
    $Year_Level = $_POST['Year_Level'];
    $email = $_POST['email'];

    $mysqli = new mysqli('localhost', 'root', '', 'StudentRecords');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $sql = 'UPDATE students SET name = ?, age = ?, Course = ?, Year_Level = ?, email = ? WHERE id = ?';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sisssi', $name, $age, $Course, $Year_Level, $email, $id);

    if ($stmt->execute()) {
        header('Location: index.php');
        exit;
    } else {
        $updateError = 'Error updating student record: ' . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Update Student Record</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        form {
            width: 300px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"] {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
        }

        input[type="submit"] {
            margin-top: 10px;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        p.error {
            color: red;
        }
    </style>
</head>
<body>
    <h1>Update Student Record</h1>

    <?php
    if (isset($updateError)) {
        echo '<p class="error">' . $updateError . '</p>';
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

        <input type="submit" value="Update Student">
    </form>
</body>
</html>
