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

$deleteError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete the student record
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
}

$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Delete Student Record</title>
    <style>
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
            border: 1px solid #888;
            width: 300px;
            text-align: center;
        }

        .modal-content p {
            margin: 0;
        }

        .modal-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .modal-buttons button {
            padding: 8px 16px;
            text-decoration: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }

        .modal-buttons button:first-child {
            background-color: #dc3545;
        }
    </style>
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

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <p>Are you sure you want to delete this student record?</p>
            <div class="modal-buttons">
                <button id="deleteConfirm">Delete</button>
                <button id="deleteCancel">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        const deleteButton = document.querySelector('input[type="submit"]');
        const deleteModal = document.getElementById('deleteModal');
        const deleteConfirmButton = document.getElementById('deleteConfirm');
        const deleteCancelButton = document.getElementById('deleteCancel');

        deleteButton.addEventListener('click', function(event) {
            event.preventDefault();
            deleteModal.style.display = 'block';
        });

        deleteConfirmButton.addEventListener('click', function() {
            document.querySelector('form').submit();
        });

        deleteCancelButton.addEventListener('click', function() {
            deleteModal.style.display = 'none';
        });
    </script>
</body>
</html>
