<?php

include './config/database.php';

$is_invalid = false;

// Login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userName = $_POST['name'];
    $password = $_POST['password'];

    // Check if 'name' key is set in $_POST
    if (isset($_POST["name"])) {
        $patientname = $mysqli->real_escape_string($userName);
        $sql = sprintf("SELECT * FROM patient WHERE patientname='%s'", $patientname);
        $results = $mysqli->query($sql);

        // Check for a successful query
        if (!$results) {
            die("Error in query: " . $mysqli->error);
        }

        $patient = $results->fetch_assoc();
        if ($patient) {
            if (password_verify($password, $patient["password_hash"])) {
                // Set user information in the session
                $_SESSION["user_id"] = $patient["user_id"]; // Adjust based on your database structure
                header("Location: udashboard.php"); // Redirect after a successful login
                exit();
            } else {
                $is_invalid = true;
            }
        } else {
            $is_invalid = true;
        }
    } else {
        die("Name is required.");
    }
}

// Check if dark mode is enabled
$dark_mode = isset($_COOKIE['dark_mode']) ? $_COOKIE['dark_mode'] : 'false';

// Toggle dark mode
if (isset($_GET['toggle_dark_mode'])) {
    $dark_mode = $dark_mode === 'true' ? 'false' : 'true';
    setcookie('dark_mode', $dark_mode, time() + (365 * 24 * 60 * 60), '/');
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
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: <?php echo $dark_mode === 'true' ? '#121212' : '#f4f4f4'; ?>;
            color: <?php echo $dark_mode === 'true' ? '#fff' : '#000'; ?>;
        }

        .login {
            background-color: <?php echo $dark_mode === 'true' ? '#333' : '#fff'; ?>;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center; /* Center text within the login container */
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
        }

        input {
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid <?php echo $dark_mode === 'true' ? '#ccc' : '#333'; ?>;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: <?php echo $dark_mode === 'true' ? '#444' : '#fff'; ?>;
            color: <?php echo $dark_mode === 'true' ? '#fff' : '#000'; ?>;
        }

        button {
            background-color: <?php echo $dark_mode === 'true' ? '#4caf50' : '#333'; ?>;
            color: <?php echo $dark_mode === 'true' ? '#fff' : '#fff'; ?>;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: <?php echo $dark_mode === 'true' ? '#45a049' : '#4caf50'; ?>;
        }

        /* Additional styles for the signup link */
        a {
            display: block;
            margin-top: 10px;
            color: <?php echo $dark_mode === 'true' ? '#4caf50' : '#4caf50'; ?>;
            text-decoration: none;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        /* Additional styles for error message */
        em {
            color: red;
            font-style: italic;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php if ($is_invalid): ?>
        <em>Invalid login</em>
    <?php endif; ?>
    <div class="login">
        <form method="post">
            <!-- Your login form goes here -->
            <div>
                <label for="name">Name</label>
                <input type="text" placeholder="Enter your name" name="name">
            </div>
            
            <div>
                <label for="password">Password</label>
                <input type="password"  name="password">
            </div>
            
            <button>LOGIN</button>
        </form>

        <!-- Signup link -->
        <a href="signin.html">Sign Up</a>

        <!-- Dark mode toggle link -->
        <a href="?toggle_dark_mode">Toggle Dark Mode</a>
    </div>
</body>

</html>
