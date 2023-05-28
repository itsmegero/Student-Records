<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $course = $_POST['course'];
    $yearLevel = $_POST['yearLevel'];
    $email = $_POST['email'];

    $mysqli = new mysqli('localhost', 'root', '', 'StudentRecords');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $sql = 'INSERT INTO students (name, age, Course, Year_Level, email) VALUES (?, ?, ?, ?, ?)';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sisss', $name, $age, $course, $yearLevel, $email);

    if ($stmt->execute()) {
        $successMessage = 'Student record added successfully.';
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

        .add-student-heading {
            text-align: center;
            font-size: 32px;
            color: #333;
            margin-bottom: 20px;
        }

        .btn-cancel {
            margin-right: 10px;
        }

        .success-message {
            color: #28a745;
            margin-top: 10px;
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .success-message.show {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="add-student-heading">Add New Student</h1>

        <?php
        if (isset($addError)) {
            echo '<p class="text-danger">' . $addError . '</p>';
        } elseif (isset($successMessage)) {
            echo '<p class="success-message">' . $successMessage . '</p>';
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
                <label for="edit-course">Course:</label>
                <div class="dropdown">
                    <select class="form-control" name="course" id="edit-course" required>
                        <option value="">Select Course</option>
                        <option value="(AB) - ENGLISH">(AB) - ENGLISH</option>
                        <option value="(AB) - LITERATURE">(AB) - LITERATURE</option>
                        <option value="(AB) - POLSCI">(AB) - POLSCI</option>
                        <option value="(AB) - PSYCH">(AB) - PSYCH</option>
                        <option value="BEED">BEED</option>
                        <option value="BPED">BPED</option>
                        <option value="BSA">BSA</option>
                        <option value="BSED">BSED</option>
                        <option value="BSEE">BSEE</option>
                        <option value="BSIT">BSIT</option>
                        <option value="BSMA">BSMA</option>
                    </select>
                    <div class="dropdown-arrow"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="edit-year-level">Year Level:</label>
                <div class="dropdown">
                    <select class="form-control" name="yearLevel" id="edit-year-level" required>
                        <option value="">Select Year Level</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                    </select>
                    <div class="dropdown-arrow"></div>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <button type="submit" class="btn btn-primary">Add Student</button>
            <a href="index.php" class="btn btn-secondary btn-cancel">Back</a>
        </form>
    </div>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        setTimeout(function() {
            var successMessage = document.querySelector('.success-message');
            if (successMessage) {
                successMessage.classList.remove('show');
                setTimeout(function() {
                    successMessage.style.display = 'none';
                }, 500); 
            }
        }, 3000);

        var successMessage = document.querySelector('.success-message');
        if (successMessage) {
            setTimeout(function() {
                successMessage.classList.add('show');
            }, 100); 
        }
    </script>
</body>
</html>
