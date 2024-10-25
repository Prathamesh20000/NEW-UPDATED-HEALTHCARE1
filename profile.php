<?php 
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Include database connection file
require_once 'db_config.php';

// Fetch user details
$username = $_SESSION['username'];
$sql = "SELECT * FROM signup WHERE `New Username` = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Close the statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | MediAssist</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* General Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: linear-gradient(45deg, #1e3c72, #2a5298);
            color: #333;
            overflow-x: hidden;
            transition: background-color 0.3s ease;
        }

        /* Back to Home Button */
        .back-home-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            margin: 20px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .back-home-button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        /* Container */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Header Styles */
        header {
            background: rgba(31, 143, 193, 0.9);
            padding: 20px 0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 10;
            transition: background-color 0.3s ease;
        }

        header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo h1 {
            color: #fff;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            transition: transform 0.3s ease;
        }

        .logo h1:hover {
            transform: scale(1.1);
        }

        .nav-menu {
            list-style: none;
            display: flex;
            align-items: center;
        }

        .nav-menu li {
            margin: 0 15px;
        }

        .nav-menu a {
            text-decoration: none;
            color: #fff;
            font-weight: 500;
            transition: color 0.3s ease;
            font-size: 18px;
        }

        .nav-menu a:hover {
            color: #ffea8a;
        }

        .cta-button {
            padding: 12px 25px;
            background-color: #ff6f61;
            color: #fff;
            border-radius: 25px;
            font-weight: 600;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .cta-button:hover {
            background-color: #ff4b3a;
            transform: scale(1.05);
        }

        /* Profile Styles */
        .profile {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 60px 0;
            text-align: center;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 50px 0;
            transform: translateY(0);
            animation: slideIn 0.6s ease-out;
        }

        .profile h2 {
            font-size: 36px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
        }

        .profile-details {
            background-color: #f9fafb;
            border-radius: 10px;
            padding: 30px;
            margin: 20px auto;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .profile-details:hover {
            transform: scale(1.03);
        }

        .profile-details p {
            font-size: 20px;
            color: #555;
            margin: 15px 0;
        }

        .profile-details strong {
            display: block;
            font-size: 22px;
            color: #333;
        }

        /* Notification Icon */
        .notification {
            position: relative;
            display: inline-block;
            margin-left: 20px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .notification:hover {
            transform: scale(1.1);
        }

        .notification img {
            width: 28px;
            height: 28px;
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: #ff0000;
            color: white;
            border-radius: 50%;
            padding: 3px 8px;
            font-size: 12px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        /* Footer */
        footer {
            background: linear-gradient(45deg, #1f8fc1, #28a8e0);
            padding: 20px 0;
            color: #fff;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .footer-links {
            margin-top: 10px;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 16px;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #ffea8a;
        }

        /* Animations */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="container">
            <div class="logo">
                <h1>MediAssist</h1>
            </div>
            <nav>
                <ul class="nav-menu">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li class="notification" onclick="window.location.href='notification.html';">
                        <img src="notification_icon.png" alt="Notifications">
                        <span class="notification-count">3</span>
                    </li>
                    <li><a href="logout.php" class="cta-button" onclick="return confirm('Are you sure you want to logout?');">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Back to Home Button -->
    <a href="index.html" class="back-home-button">⬅️ Back to Home</a>

    <!-- Profile Section -->
    <section class="profile">
        <div class="container">
            <h2>User Profile</h2>
            <div class="profile-details">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($user['New Username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
                <p><strong>Age:</strong> <?php echo htmlspecialchars($user['Age']); ?></p>
                <p><strong>Gender:</strong> <?php echo htmlspecialchars($user['Gender']); ?></p>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <p>&copy; 2024 MediAssist. All rights reserved.</p>
            <div class="footer-links">
                <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a> | <a href="#">Contact Us</a>
            </div>
        </div>
    </footer>
</body>
</html>
