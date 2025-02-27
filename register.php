<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password
    $role = $_POST['role'];

    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    if ($stmt->execute()) {
    //    echo "Registration successful! <a href='login.php'>Login here</a>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Srinivasa Electronics</title>
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

        /* Register Section Styling */
        .register-container {
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

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 6px;
            outline: none;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus,
        select:focus {
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

        input[type="text"]:focus + label,
        input[type="email"]:focus + label,
        input[type="password"]:focus + label,
        select:focus + label {
            font-size: 12px;
            color: #bfa378;
            top: -18px;
        }

        .register-button {
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

        .register-button:hover {
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

    <!-- Register Section -->
    <div class="register-container">
        <h2>Join Srinivasa Electronics Today!</h2>
        <p class="subtext">Sign up and explore our exclusive collection of premium Electronics.</p>
        <form method="POST">
            <div class="input-group">
                <input type="text" name="name" required>
                <label>Full Name</label>
            </div>
            <div class="input-group">
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="input-group">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <div class="input-group">
                <select name="role" required>
                    <option value="seller">Seller</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <label>Role</label>
            </div>
            <button type="submit" class="register-button">Register</button>
        </form>
        <p style="text-align: center;">Already have an account? <a href="login.php">Login</a></p>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Srinivasa Electronics - All Rights Reserved</p>
    </footer>
</body>
</html>
