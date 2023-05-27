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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .student-records-heading {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<link rel="stylesheet" href="style.css">
    <div class="navbar">
        <h1>Welcome, <?php echo $_SESSION['username']; ?>!</h1>
        <div class="logout"><a href="logout.php" class="button">Logout</a></div>
    </div>

    <h2 class="student-records-heading">Student Records</h2>

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
            echo '<td id="name-' . $row['id'] . '">' . $row['name'] . '</td>';
            echo '<td id="age-' . $row['id'] . '">' . $row['age'] . '</td>';
            echo '<td id="course-' . $row['id'] . '">' . $row['Course'] . '</td>';
            echo '<td id="year-level-' . $row['id'] . '">' . $row['Year_Level'] . '</td>';
            echo '<td id="email-' . $row['id'] . '">' . $row['email'] . '</td>';
            echo '<td><a href="#" onclick="openEditModal(' . $row['id'] . ')" class="button">Edit</a> | <a href="#" onclick="openModal(' . $row['id'] . ')" class="button">Delete</a></td>';
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

    <!-- Edit Modal -->
<div id="edit-modal" class="modal-update">
    <div class="modal-update-content">
        <div class="modal-update-header">
            <h2>Edit Student Record</h2>
        </div>
        <div class="modal-update-body">
            <form action="update.php" method="POST">
                <input type="hidden" name="studentId" id="edit-student-id">
                <div class="form-group">
                    <label for="edit-name">Name:</label>
                    <input type="text" class="form-control" name="name" id="edit-name" required>
                </div>
                <div class="form-group">
                    <label for="edit-age">Age:</label>
                    <input type="number" class="form-control" name="age" id="edit-age" required>
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
                    <label for="edit-email">Email:</label>
                    <input type="email" class="form-control" name="email" id="edit-email" required>
                </div>
                <div class="button-container">
                    <button type="submit" class="button button-primary">Save</button>
                    <button type="button" onclick="closeEditModal()" class="button button-secondary">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Delete Modal -->
    <div id="modal" class="modal">
        <div class="modal-content">
            <div class="modal-body">
                <p>Are you sure you want to delete this student record?</p>
                <p>Please note that deleting a student record will permanently remove all associated data.</p>
                <p>This action cannot be undone.</p>
            </div>
            <div class="modal-buttons">
                <button onclick="deleteStudent()" class="button button-primary">Delete</button>
                <button onclick="closeModal()" class="button button-secondary">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Open the modal for deleting
        function openModal(id) {
            const modal = document.getElementById('modal');
            modal.style.display = 'block';
            modal.dataset.studentId = id;
        }

        // Close the delete modal
        function closeModal() {
            const modal = document.getElementById('modal');
            modal.style.display = 'none';
        }

        // Delete the student record
        function deleteStudent() {
            const modal = document.getElementById('modal');
            const studentId = modal.dataset.studentId;

            // Send an AJAX request to delete.php
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // Deletion successful, refresh the page
                        closeModal();
                        location.reload();
                    } else {
                        // Deletion failed, display an error message
                        console.error(xhr.responseText);
                    }
                }
            };
            xhr.send('deleteId=' + studentId);
        }

        // Open the modal for editing
        function openEditModal(id) {
            const modal = document.getElementById('edit-modal');
            modal.style.display = 'block';
            document.getElementById('edit-student-id').value = id;
            document.getElementById('edit-name').value = document.getElementById('name-' + id).innerText;
            document.getElementById('edit-age').value = document.getElementById('age-' + id).innerText;
            document.getElementById('edit-course').value = document.getElementById('course-' + id).innerText;
            document.getElementById('edit-year-level').value = document.getElementById('year-level-' + id).innerText;
            document.getElementById('edit-email').value = document.getElementById('email-' + id).innerText;
        }

        // Close the edit modal
        function closeEditModal() {
            const modal = document.getElementById('edit-modal');
            modal.style.display = 'none';
        }
    </script>
</body>
</html>
