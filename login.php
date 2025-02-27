<?php
include 'config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, name, role, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_name'] = $user['name'];

            // Role-based redirection
            switch ($user['role']) {
                case 'admin':
                    header("Location: admin/dashboard.php");
                    break;
                case 'seller':
                    header("Location: seller/dashboard.php");
                    break;
                default:
                    header("Location: user/profile.php");
                    break;
            }
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "User not found!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Srinivasa Electronics</title>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600&family=Roboto:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* General Styling */
        body {
            font-family: 'Barlow', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        a {
            text-decoration: none;
            color: #333;
        }
        a:hover {
            color: #bfa378;
        }
        /* Navbar Styling */
        nav {
            background-color: #333;
            padding: 10px 0;
        }
        .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: 600;
            color: #fff;
        }
        .navbar-links {
            list-style: none;
            display: flex;
            gap: 20px;
        }
        .navbar-links li a {
            color: #fff;
            font-weight: 600;
            font-size: 16px;
        }
        .navbar-links li a:hover {
            color: #bfa378;
        }
        /* Login Section Styling */
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            font-size: 28px;
            color: #333;
            margin-bottom: 10px;
        }
        .subtext {
            text-align: center;
            color: #777;
            margin-bottom: 30px;
        }
        .input-group {
            position: relative;
            margin-bottom: 20px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 6px;
            outline: none;
            transition: all 0.3s ease;
        }
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #bfa378;
        }
        label {
            font-size: 14px;
            color: #555;
            position: absolute;
            top: -8px;
            left: 12px;
            background-color: #fff;
            padding: 0 5px;
            transition: all 0.3s ease;
        }
        input[type="email"]:focus + label,
        input[type="password"]:focus + label {
            font-size: 12px;
            color: #bfa378;
            top: -18px;
        }
        .error {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-bottom: 15px;
        }
        .login-button {
            width: 100%;
            padding: 12px;
            background-color: #bfa378;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .login-button:hover {
            background-color: #a48f64;
        }
        /* Footer Styling */
        footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            font-size: 14px;
        }
        footer p {
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav>
        <div class="navbar-container">
            <div class="logo">Srinivasa Electronics</div>
            <ul class="navbar-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
                <li><a href="login.php">Login</a></li>
                <li><a href="register.php">Sign Up</a></li>
            </ul>
        </div>
    </nav>

    <!-- Login Section -->
    <div class="login-container">
        <h2>Welcome Back to Srinivasa Electronics</h2>
        <p class="subtext">Log in to discover our exclusive collection of premium Electronics!</p>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="input-group">
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <button type="submit" class="login-button">Login</button>
        </form>
        <p style="text-align:center;">Don't have an account? <a href="register.php">Register</a></p>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Srinivasa Electronics - All Rights Reserved</p>
      
    </footer>
</body>
</html>
