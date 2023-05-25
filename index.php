<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Records</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1, h2 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        a.button {
            display: inline-block;
            padding: 8px 16px;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
        }

        a.button:hover {
            background-color: #0056b3;
        }

        p.logout {
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
    <p class="logout"><a href="logout.php" class="button">Logout</a></p>

    <h2>Student Records</h2>
    <p><a href="create.php" class="button">Add New Student</a></p>

    <?php
    // Display student records from the database
    $mysqli = new mysqli('localhost', 'root', '', 'StudentRecords');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $sql = 'SELECT * FROM students';
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        echo '<table>';
        echo '<tr><th>ID</th><th>Name</th><th>Age</th><th>Course</th><th>Year Level</th><th>Email</th><th>Actions</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['name'] . '</td>';
            echo '<td>' . $row['age'] . '</td>';
            echo '<td>' . $row['Course'] . '</td>';
            echo '<td>' . $row['Year_Level'] . '</td>';
            echo '<td>' . $row['email'] . '</td>';
            echo '<td><a href="update.php?id=' . $row['id'] . '" class="button">Edit</a> | <a href="delete.php?id=' . $row['id'] . '" class="button">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No student records found.</p>';
    }

    $mysqli->close();
    ?>
</body>
</html>
