<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $host = 'localhost';
    $dbname = 'StudentRecords'; 
    $dbUsername = 'root'; 
    $dbPassword = ''; 
    try {
        $conn = new PDO("mysql:host=$host;dbname=$dbname", $dbUsername, $dbPassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT * FROM account WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

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
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
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
            transition: color 0.3s ease-in-out; /
        }
        .password-icon:hover {
            color: red; 
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
            position: relative; 
        }
        .login-title {
            text-align: center; 
            margin-bottom: 30px;
        }
        .error-message {
            color: red;
            font-size: 16px; 
            margin-top: 10px; 
            text-align: center; 
        }
        .robot-icon {
            color: red; 
            font-size: 24px; 
            margin-left: 5px; 
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

            <button type="submit" class="btn btn-primary btn-block">Login</button> 
        </form>
        <br>

        <p class="text-center">Don't have an account? <a href="register.php">Register here</a>.</p> 
    </div>

  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

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
