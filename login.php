<?php
session_start();
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);
    if ($stmt->fetch()) {
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            header("Location: dashboard.php");
        } else {
            echo "<p style='color:red; text-align:center;'>Invalid password!</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>User not found!</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(to bottom right, #4CAF50, #45a049);
        }
        .container {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            animation: fadeIn 1s ease-out;
        }
        h2 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 1rem;
            outline: none;
            transition: 0.3s;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .quote {
            font-style: italic;
            color: #333;
            margin-top: 20px;
            font-size: 1rem;
        }
        .signup-link {
            display: block;
            margin-top: 20px;
            text-decoration: none;
            color: #4CAF50;
            font-weight: bold;
        }
        .signup-link:hover {
            text-decoration: underline;
        }
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(50px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <input type="submit" value="Login">
        </form>

        <div class="quote" id="quote"></div>

        <a href="signup.php" class="signup-link">Don't have an account? Sign up here</a>
    </div>

    <script>
        const quotes = [
            "“The future belongs to those who believe in the beauty of their dreams.” – Eleanor Roosevelt",
            "“Success is not final, failure is not fatal: It is the courage to continue that counts.” – Winston Churchill",
            "“It does not matter how slowly you go as long as you do not stop.” – Confucius",
            "“Believe you can and you're halfway there.” – Theodore Roosevelt"
        ];

        let currentQuoteIndex = 0;

        function showNextQuote() {
            document.getElementById('quote').innerText = quotes[currentQuoteIndex];
            currentQuoteIndex = (currentQuoteIndex + 1) % quotes.length;
        }

        setInterval(showNextQuote, 3000); // Change quote every 3 seconds

        showNextQuote(); // Initialize with the first quote
    </script>
</body>
</html>
