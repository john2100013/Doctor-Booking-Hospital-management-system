<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url("/Emirates/img/coverpage.jpg");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        header {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            font-size: 24px;
        }

        nav {
            background-color: #4CAF50;
            overflow: hidden;
            float: left;
            width: 250px;
        }

        nav a {
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        nav a:hover {
            background-color: #45a049;
            color: black;
        }

        .content {
            margin-left: 250px;
            padding: 1px 16px;
        }

        .welcome {
            text-align: center;
            padding: 100px 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
            margin: auto;
            margin-top: 20px;
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
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #4caf50;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #45a049;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    Emirates Hospital
</header>

<nav>
    <a href="apointment.php">Book Appointment</a>
    <a href="viewappointment.php">View Appointment</a>
    <a href="view_medication_history.php">Prescription</a>
    
    <a href="logout.php">Logout</a>
</nav>

<div class="content">
    <div class="welcome">
        <h1>Welcome to Emirates Management System where you are of importance.</h1>
    </div>
    <!-- Your other content goes here -->
</div>

<footer>
    &copy; Emirates Hospital. All rights reserved.
</footer>

</body>
</html>
