<?php
include 'config.php';
session_start();

if (isset($_GET['query'])) {
    $query = $_GET['query'];

    // Sanitize user input
    $query_safe = htmlspecialchars($query);

    // Prepare SQL query to search for products and their sellers
    // Here we assume products table has columns: id, name, description, and seller_id,
    // and users table holds seller info (id, name)
    $sql = "
        SELECT p.id AS product_id, p.name AS product_name, u.name AS seller_name
        FROM products p
        JOIN users u ON p.seller_id = u.id
        WHERE p.name LIKE ? OR p.description LIKE ? OR u.name LIKE ?
    ";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $search_term = "%" . $query . "%";
    $stmt->bind_param("sss", $search_term, $search_term, $search_term);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Search Results - Srinivasa Electronics</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="assets/styles.css">
  <style>
    /* Reset some default styles */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    /* Body Styling */
    body {
      font-family: 'Roboto', sans-serif;
      background-color: #f9f9f9;
      color: #333;
    }
    /* Navbar */
    nav {
            background-color:rgb(184, 66, 127);
            padding: 15px 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        nav .navbar-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        nav .logo {
            font-size: 2em;
            font-weight: 700;
            color: white;
            letter-spacing: 1px;
        }

        nav .navbar-links {
            list-style-type: none;
            display: flex;
            gap: 25px;
        }

        nav .navbar-links li {
            display: inline-block;
        }

        nav .navbar-links a {
            color: white;
            font-size: 1.2em;
            transition: color 0.3s ease;
            letter-spacing: 1px;
        }

        nav .navbar-links a:hover {
            color:rgb(6, 125, 176); /* Secondary Color: a warm accent */
        }
    /* Hero Section Styling */
    .hero-section {
      background: url('assets/img/background1.jpg') no-repeat center center/cover;
      padding: 120px 0;
      color: white;
      text-align: center;
      position: relative;
      margin-bottom: 50px;
      height: 50vh;
    }
    .hero-section::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(0, 0, 0, 0.4);
      z-index: -1;
    }
    .hero-section h2 {
      font-size: 4em;
      font-weight: 700;
      color: #fcc203;
      margin-bottom: 20px;
    }
    .hero-section p {
      font-size: 1.5em;
      margin-bottom: 30px;
      font-weight: 300;
      color: #fff;
    }
    .hero-button {
      background-color: #fcc203;
      color: white;
      padding: 18px 28px;
      font-size: 1.3em;
      font-weight: 600;
      border-radius: 50px;
      transition: background-color 0.3s, transform 0.3s ease;
      letter-spacing: 1px;
    }
    .hero-button:hover {
      background-color: #e1a200;
      transform: scale(1.1);
    }
    /* Search Results Section */
    .search-results {
      text-align: center;
      padding: 50px 20px;
      background-color: #fff;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      margin: 20px;
      border-radius: 10px;
    }
    .search-results h2 {
      font-size: 2.5em;
      margin-bottom: 40px;
      color: #fcc203;
    }
    .result-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px;
      background-color: #f4f4f4;
      margin: 10px 0;
      border-radius: 10px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .result-item h3 {
      color: #333;
      font-size: 1.8em;
      margin: 0;
    }
    .result-item p {
      font-size: 1.1em;
      color: #555;
    }
    .view-product {
      background-color: #fcc203;
      color: #fff;
      padding: 8px 15px;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }
    .view-product:hover {
      background-color: #f8a800;
    }
    .no-results {
      font-size: 1.5em;
      color: #ff6347;
      margin-top: 20px;
    }
    /* Footer Styling */
    .footer {
      background-color: #333;
      color: white;
      padding: 20px 0;
      text-align: center;
    }
    .footer a {
      color: #fcc203;
      text-decoration: none;
    }
    .footer a:hover {
      text-decoration: underline;
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
        <?php if(isset($_SESSION['user_id'])): ?>
          <li><a href="user/profile.php">Profile</a></li>
          <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
          <li><a href="register.php">Sign Up</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </nav>

  <!-- Hero Section -->
  <div class="hero-section">
    <h2>Find Your Signature Electronics</h2>
    <p>Discover an exclusive collection of premium Electronics.</p>
    <a href="login.php" class="hero-button">Get Started</a>
  </div>

  <!-- Search Results Section -->
  <div class="search-results">
    <h2>Search Results for: <?= htmlspecialchars($query_safe) ?></h2>
    <?php if (isset($result) && $result->num_rows > 0): ?>
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="result-item">
          <div>
            <h3><?= htmlspecialchars($row['product_name']) ?></h3>
            <p>By: <?= htmlspecialchars($row['seller_name']) ?></p>
          </div>
          <div>
            <a href="view_product.php?id=<?= htmlspecialchars($row['product_id']) ?>" class="view-product">View Product</a>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p class="no-results">No results found for your search.</p>
    <?php endif; ?>
  </div>

  <!-- Footer Section -->
  <div class="footer">
    <p>&copy; 2025 Srinivasa Electronics. All rights reserved. <a href="privacy_policy.php">Privacy Policy</a></p>
  </div>

</body>
</html>
