<?php
include '../config.php';
session_start();

// Ensure only a logged-in seller can access this page.
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'seller') {
    header("Location: ../login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch current seller profile data.
$query = "SELECT name, email, bio, profile_picture FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$seller = $result->fetch_assoc();

if (!$seller) {
    die("Seller not found.");
}

// Process form submission.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $bio   = $_POST['bio'];

    // Keep current picture by default.
    $profile_picture = $seller['profile_picture'];

    // Check if a new image was uploaded.
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profile_picture = $_FILES['profile_picture']['name'];
        $target = "../uploads/" . basename($profile_picture);
        if (!move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target)) {
            $error = "Error uploading image.";
        }
    }

    // Update the seller's profile.
    $update_sql = "UPDATE users SET name = ?, email = ?, bio = ?, profile_picture = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    if ($update_stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $update_stmt->bind_param("ssssi", $name, $email, $bio, $profile_picture, $user_id);
    if ($update_stmt->execute()) {
        $success = "Profile updated successfully!";
        // Refresh seller data.
        $stmt->execute();
        $result = $stmt->get_result();
        $seller = $result->fetch_assoc();
    } else {
        $error = "Error updating profile: " . $update_stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Srinivasa Electronics</title>
    <link rel="stylesheet" href="../assets/styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Barlow', sans-serif;
            background: #fafafa;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .container {
            max-width: 500px;
            margin: 40px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        input[type="text"],
        input[type="email"],
        textarea,
        input[type="file"] {
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        button {
            padding: 12px;
            background-color: #bfa378;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #a48f64;
        }
        .message {
            text-align: center;
            padding: 10px;
            margin-bottom: 15px;
        }
        .success {
            background: #28a745;
            color: white;
        }
        .error {
            background: #dc3545;
            color: white;
        }
        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            text-decoration: none;
            padding: 10px;
            background: #007bff;
            color: #fff;
            border-radius: 4px;
        }
        .back-btn:hover {
            background: #0056b3;
        }
        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            display: block;
            margin: 0 auto 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Edit Profile</h2>
        <?php if(isset($success)): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if(isset($error)): ?>
            <div class="message error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <?php if (!empty($seller['profile_picture'])): ?>
                <img src="../uploads/<?= htmlspecialchars($seller['profile_picture']) ?>" alt="Profile Picture" class="profile-img">
            <?php else: ?>
                <img src="../assets/default_product.jpg" alt="Profile Picture" class="profile-img">
            <?php endif; ?>
            <input type="text" name="name" value="<?= htmlspecialchars($seller['name']) ?>" placeholder="Full Name" required>
            <input type="email" name="email" value="<?= htmlspecialchars($seller['email']) ?>" placeholder="Email" required>
            <textarea name="bio" placeholder="Your Bio"><?= htmlspecialchars($seller['bio']) ?></textarea>
            <input type="file" name="profile_picture">
            <button type="submit">Update Profile</button>
        </form>
        <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</body>
</html>
