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

    .navbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        background-color: #f5f5f5;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        padding: 10px;
    }

    .navbar h1 {
        margin: 0;
        font-size: 20px;
        color: #333;
    }

    .logout {
        text-align: right;
    }

    .logout a {
        display: inline-block;
        padding: 8px 16px;
        text-decoration: none;
        background-color: #007bff;
        color: #fff;
        border-radius: 4px;
    }

    .logout a:hover {
        background-color: #0056b3;
    }

    .add-student {
        margin-top: 20px;
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

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: none;
        width: 30%; /* Adjust the width as needed */
        border-radius: 5px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-header {
        background-color: #f5f5f5;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        padding: 5px; /* Adjust the padding as needed */
        text-align: center;
        margin-bottom: 30px; /* Increase the margin-bottom value */
    }

    .modal-body {
        margin-bottom: 20px; /* Increase the margin-bottom value */
    }

    .modal-message {
        background-color: #007bff;
        color: #fff;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    .modal-buttons {
        text-align: right;
    }

    .modal-buttons button {
        padding: 8px 16px;
        background-color: #007bff;
        color: #fff;
        border-radius: 4px;
        margin-left: 10px;
        border: none;
    }

    .modal-buttons button:first-child {
        margin-left: 0;
    }

    .modal-buttons button:hover {
        background-color: #0056b3;
    }
</style>



</head>
<body>
    <div class="navbar">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <div class="logout"><a href="logout.php" class="button">Logout</a></div>
    </div>

    <h2>Student Records</h2>

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
            echo '<td><a href="update.php?id=' . $row['id'] . '" class="button">Edit</a> | <a href="#" onclick="openModal(' . $row['id'] . ')" class="button">Delete</a></td>';
            echo '</tr>';
        }
        echo '</table>';
    } else {
        echo '<p>No student records found.</p>';
    }

    $mysqli->close();
    ?>

    <div class="add-student">
        <p><a href="create.php" class="button">Add New Student</a></p>
    </div>

    <!-- Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Delete Student Record</h2>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this student record?</p>
                <p>This action cannot be undone.</p>
            </div>
            <div class="modal-buttons">
                <button onclick="deleteStudent()">Delete</button>
                <button onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Open the modal
        function openModal(id) {
            const modal = document.getElementById('modal');
            modal.style.display = 'block';
            // Pass the student ID to the deleteStudent function
            modal.dataset.studentId = id;
        }

        // Close the modal
        function closeModal() {
            const modal = document.getElementById('modal');
            modal.style.display = 'none';
        }

        // Delete the student record
        function deleteStudent() {
            const modal = document.getElementById('modal');
            const studentId = modal.dataset.studentId;
            closeModal();
            // Redirect to delete.php with the student ID
            window.location.href = 'delete.php?id=' + studentId;
        }
    </script>
</body>
</html>
