<?php
include '../config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Please log in first.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="edit-profile-container">
    <h2>Edit Profile</h2>
    
    <!-- Profile Picture Upload Section -->
    <div class="profile-picture-section">
        <img id="profilePreview" src="../uploads/<?= $user['profile_picture'] ?>" alt="Profile Picture" class="profile-img">
        <label for="profile_picture" class="upload-btn">Change Photo</label>
        <input type="file" id="profile_picture" name="profile_picture" accept="image/*" onchange="previewImage(event)">
    </div>

    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?= $user['name'] ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?= $user['email'] ?>" required>
        </div>

        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="4"><?= $user['bio'] ?></textarea>
        </div>

        <button type="submit" class="btn">Save Changes</button>
        <a href="profile.php" class="cancel-btn">Cancel</a>
    </form>
</div>

<script>
// Live preview of profile picture
function previewImage(event) {
    var reader = new FileReader();
    reader.onload = function(){
        var output = document.getElementById('profilePreview');
        output.src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>

<style>
body {
    font-family: 'Barlow', sans-serif;
    background: #f5f5f5;
    margin: 0;
    padding: 0;
}

.edit-profile-container {
    width: 50%;
    margin: auto;
    background: #fff;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    text-align: center;
}

.edit-profile-container h2 {
    font-size: 1.8em;
    color: #333;
    margin-bottom: 20px;
}

.profile-picture-section {
    position: relative;
    margin-bottom: 20px;
}

.profile-img {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #007bff;
    display: block;
    margin: 0 auto 10px;
}

.upload-btn {
    display: block;
    margin: auto;
    background: #007bff;
    color: white;
    padding: 8px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.9em;
    transition: background 0.3s;
}

.upload-btn:hover {
    background: #0056b3;
}

#profile_picture {
    display: none;
}

.form-group {
    text-align: left;
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    transition: border 0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #007bff;
    outline: none;
}

.btn {
    display: inline-block;
    background-color: #28a745;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 1em;
    transition: background 0.3s;
    cursor: pointer;
    border: none;
    margin-right: 10px;
}

.btn:hover {
    background-color: #218838;
}

.cancel-btn {
    background-color: #dc3545;
    color: white;
    padding: 10px 15px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 1em;
    transition: background 0.3s;
    display: inline-block;
}

.cancel-btn:hover {
    background-color: #c82333;
}
</style>

</body>
</html>
