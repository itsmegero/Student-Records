<?php
session_start();

// Check if the user is already logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate the login credentials
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Connect to the database
    $host = 'localhost';
    $dbname = 'StudentRecords'; // Replace with your actual database name
    $dbUsername = 'root'; // Replace with your actual database username
    $dbPassword = ''; // Replace with your actual database password

    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Prepare and execute the query
        $stmt = $conn->prepare("SELECT * FROM account WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user exists and the password is correct
        if ($user && $password === $user['password']) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } else {
            $loginError = 'Invalid username or password.';
        }
    } catch (PDOException $e) {
        $loginError = 'Error: Failed to connect to the database.';
        $loginError .= ' Detailed error message: ' . $e->getMessage();
        // You can uncomment the following line to see the detailed error message for debugging
        // $loginError = 'Error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <!-- Add Bootstrap CDN -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .password-icon {
            cursor: pointer;
            transition: color 0.3s ease-in-out; /* Added animation to the password icon */
        }
        .password-icon:hover {
            color: red; /* Change color on hover (you can modify the color as needed) */
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
            position: relative; /* Added positioning to the container */
        }
        .login-title {
            text-align: center; /* Center-align the login title */
            margin-bottom: 30px;
        }
        .error-message {
            color: red; /* Set the color of the error message */
            font-size: 16px; /* Set the font size of the error message */
            margin-top: 10px; /* Add margin top to create space between the error message and the form */
            text-align: center; /* Center-align the error message */
        }
        .robot-icon {
            color: red; /* Set the color of the robot icon */
            font-size: 24px; /* Set the font size of the robot icon */
            margin-left: 5px; /* Add margin to create space between the icon and the text */
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

        <h1 class="login-title">Login</h1>

        <?php if (isset($loginError)): ?>
            <p class="text-danger"><?php echo $loginError; ?></p>
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
                            <i class="password-icon fas fa-eye" onclick="togglePasswordVisibility()"></i>
                        </span>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Login</button> <!-- Added the btn-block class to make the button full-width -->
        </form>
        <br>

        <p class="text-center">Don't have an account? <a href="register.php">Register here</a>.</p> <!-- Added the text-center class to center-align the paragraph -->
    </div>

    <!-- Add Font Awesome CDN for the eye icon (optional) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

    <!-- Add Bootstrap JS CDN (optional) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

     <!-- Add Font Awesome CDN for the eye and robot icons (optional) -->
     <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<!-- Add Bootstrap JS CDN (optional) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    function togglePasswordVisibility() {
        var passwordInput = document.getElementById('password');
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
