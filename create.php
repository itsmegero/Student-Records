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
    <!-- Add Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Add New Student</h1>

        <?php
        if (isset($addError)) {
            echo '<p class="text-danger">' . $addError . '</p>';
        }
        ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>

            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" class="form-control" id="age" name="age" required>
            </div>

            <div class="form-group">
                <label for="Course">Course:</label>
                <input type="text" class="form-control" id="Course" name="Course" required>
            </div>

            <div class="form-group">
                <label for="Year_Level">Year Level:</label>
                <input type="number" class="form-control" id="Year_Level" name="Year_Level" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Student</button>
        </form>
    </div>

    <!-- Add Bootstrap JS CDN (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
