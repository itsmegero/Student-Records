<?php
// Check if the deleteId parameter is set
if (isset($_POST['deleteId'])) {
    $deleteId = $_POST['deleteId'];

    // Perform the deletion
    $mysqli = new mysqli('localhost', 'root', '', 'StudentRecords');
    if ($mysqli->connect_error) {
        die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
    }

    $sql = 'DELETE FROM students WHERE id = ?';
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $deleteId);
    $stmt->execute();
    $stmt->close();

    $mysqli->close();

    // Return a success response
    echo 'Record deleted successfully.';
} else {
    // Return an error response
    echo 'Error: No record ID provided.';
}

?>
