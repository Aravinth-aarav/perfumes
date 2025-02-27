<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $bio = $conn->real_escape_string($_POST['bio']);

    // Profile picture upload handling
    if (!empty($_FILES["profile_picture"]["name"])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($imageFileType, $allowed_types)) {
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
                $profile_picture = basename($_FILES["profile_picture"]["name"]);
                $query = "UPDATE users SET name=?, email=?, bio=?, profile_picture=? WHERE id=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssssi", $name, $email, $bio, $profile_picture, $user_id);
            } else {
                echo "Error uploading file.";
                exit();
            }
        } else {
            echo "Invalid file format.";
            exit();
        }
    } else {
        // If no new profile picture, update other fields
        $query = "UPDATE users SET name=?, email=?, bio=? WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $bio, $user_id);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "Error updating profile: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
