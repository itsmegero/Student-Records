<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the registration data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $registrationError = '';

    if ($password !== $confirmPassword) {
        $registrationError = 'Password and confirm password do not match.';
    } elseif (empty($username) || empty($password) || empty($confirmPassword)) {
        $registrationError = 'Please fill in all fields.';
    } else {
        // Connect to the database
        $host = 'localhost';
        $dbname = 'StudentRecords';
        $dbUsername = 'root';  // Replace with your actual database username
        $dbPassword = '';  // Replace with your actual database password

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Prepare and execute the insertion query
            $stmt = $conn->prepare("INSERT INTO account (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            // Registration successful, store the user in the database or perform other actions
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $registrationError = 'Error: Failed to connect to the database.';
            // You can uncomment the following line to see the detailed error message for debugging
            // $registrationError = 'Error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <!-- Add Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Add Font Awesome CDN for the eye icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-title {
            text-align: center; /* Center-align the register title */
            margin-bottom: 30px;
        }

        .form-control {
            height: 50px; /* Increased the height of form controls */
            font-size: 18px; /* Increased the font size of form controls */
        }
        .container {
            animation: slide-in 0.5s ease-in-out; /* Added slide-in animation to the container */
            max-width: 400px; /* Increased the maximum width of the container */
            margin: 0 auto; /* Center the container horizontally */
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2); /* Added a shadow to the container */
            padding: 20px; /* Added padding to the container */
        }
        @keyframes slide-in {
            0% {
                opacity: 0;
                transform: translateX(-100px);
            }
            100% {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class= "register-title" >Register</h1>

        <?php if (!empty($registrationError)): ?>
            <p class="text-danger"><?php echo $registrationError; ?></p>
        <?php endif; ?>

        <form method="post" action="">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="password-icon fas fa-eye" onclick="togglePasswordVisibility('password')"></i>
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="password-icon fas fa-eye" onclick="togglePasswordVisibility('confirm_password')"></i>
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Register</button>

            <div class="text-center mt-3">
                <p>Already have an account? <a href="login.php">Login</a></p>
            </div>
        </form>
    </div>

    <!-- Add Bootstrap JS CDN (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function togglePasswordVisibility(inputId) {
            var passwordInput = document.getElementById(inputId);
            var passwordIcon = document.querySelector('.password-icon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
