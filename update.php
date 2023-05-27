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
    <!-- Add Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 400px;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            width: 100%;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Student Record</h1>

        <?php
        if (isset($updateError)) {
            echo '<p class="error">' . $updateError . '</p>';
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

            <button type="submit" class="btn btn-primary">Update Student</button>
        </form>
    </div>

    <!-- Add Bootstrap JS CDN -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
