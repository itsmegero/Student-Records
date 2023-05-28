<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    $registrationError = '';

    if ($password !== $confirmPassword) {
        $registrationError = 'Password and confirm password do not match.';
    } elseif (empty($username) || empty($password) || empty($confirmPassword)) {
        $registrationError = 'Please fill in all fields.';
    } else {
        $host = 'localhost';
        $dbname = 'StudentRecords';
        $dbUsername = 'root';  
        $dbPassword = '';  

        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("INSERT INTO account (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->execute();
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $username;
            header('Location: index.php');
            exit;
        } catch (PDOException $e) {
            $registrationError = 'Error: Failed to connect to the database.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .form-control {
            height: 50px; 
            font-size: 18px; 
        }
        .container {
            animation: slide-in 0.5s ease-in-out; 
            max-width: 400px;
            margin: 0 auto; 
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.2); 
            padding: 20px; 
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
