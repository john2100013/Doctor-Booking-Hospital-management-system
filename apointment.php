<?php
include "./config/database.php";

// Function to get a list of patients from the database

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Assuming you have a database connection (adjust as needed)

    // Validate and sanitize user input (you may need more robust validation)
    $patientid = filter_input(INPUT_POST, 'patientid', FILTER_SANITIZE_STRING);
    $appointmentdatetime = filter_input(INPUT_POST, 'appointment_date', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $patientname = filter_input(INPUT_POST, 'patientname', FILTER_SANITIZE_STRING);

    // Check if the patient ID exists in the database (adjust table and column names)
    $checkPatientIdQuery = $mysqli->prepare("SELECT * FROM patient WHERE patientid = ?");
    $checkPatientIdQuery->bind_param("s", $patientid);
    $checkPatientIdQuery->execute();
    $result = $checkPatientIdQuery->get_result();

    $bookAppointmentQuery = null;

    if ($result->num_rows > 0) {
        // The patient ID exists, proceed to book the appointment (adjust table and column names)
        $bookAppointmentQuery = $mysqli->prepare("INSERT INTO appointments (patientid, appointment_date, description, patientname) VALUES (?, ?, ?, ?)");
        $bookAppointmentQuery->bind_param("ssss", $patientid, $appointmentdatetime, $description, $patientname);

        if ($bookAppointmentQuery->execute()) {
            // Display success message with patient ID and name
            $bookingSuccessMessage = "Appointment booked successfully!";
            // Redirect to dashboard after booking
            header("Location: udashboard.php?booking_success=true");
            exit();
        } else {
            $errorMessage = "Error booking appointment: " . $mysqli->error;
        }
    } else {
        $errorMessage = "Patient ID not found in the database.";
    }

    // Close database connections and statements
    $checkPatientIdQuery->close();
    // Close $bookAppointmentQuery only if it was initialized
    if ($bookAppointmentQuery !== null) {
        $bookAppointmentQuery->close();
    }
    $mysqli->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <style>
      
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
            background-image: url("/pms/img/book.png");
            background-repeat: no-repeat; /* Prevent background image from repeating */
        background-size: cover;
        }

        header {
            background-color: #4caf50;
            color: white;
            text-align: center;
            padding: 0px;
        }

        nav {
            text-align: center;
            background-color: #333;
            color: white;
            padding: 10px;
        }

        nav a {
            color: white;
            text-decoration: none;
            padding: 10px;
            margin: 0 10px;
        }

        h1 {
            text-align: center;
            color: #4caf50;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }

        input, textarea, button, select {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #4caf50;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #45a049;
        }

        .success-message {
            color: #4caf50;
            font-weight: bold;
            text-align: center;
        }

        .error-message {
            color: #f44336;
            font-weight: bold;
            text-align: center;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #333;
            color: white;
        }
    </style>
</head>
<body>
<header>
    <?php include 'header.php';
    include 'sidebar.php'; ?>

   
    </header>
    <h1>Book Appointment</h1>
    <?php
    if (isset($bookingSuccessMessage)) {
        echo '<p class="success-message">' . $bookingSuccessMessage . '</p>';
    } elseif (isset($errorMessage)) {
        echo '<p class="error-message">' . $errorMessage . '</p>';
    }
    ?>

    <form action="apointment.php" method="post">
        <label for="patient_id">Patient ID:</label>
        <input type="text" name="patientid" required>

        <label for="patientname">Patient Name:</label>
        <input type="text" name="patientname" required>

        <label for="appointment_date">Appointment Date:</label>
        <input type="datetime-local" name="appointment_date" required min="<?php echo date('Y-m-d\TH:i'); ?>">

        <label for="description">Description:</label>
        <textarea name="description" rows="2" required></textarea>

        <button type="submit">Book Appointment</button>
    </form>

    <?php include 'footer.php'; ?>
</body>
</html>
