<?php
include "./config/database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointmentId = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : null;
    $amount = isset($_POST['amount']) ? $_POST['amount'] : null;
    $paymentOption = isset($_POST['payment_option']) ? $_POST['payment_option'] : null;

    // Simulate payment processing
    $paymentSuccess = false;

    if ($paymentOption === 'payment_on_arrival') {
        // Handle payment on arrival logic
        // For simplicity, mark payment as successful
        $paymentSuccess = true;
    } elseif ($paymentOption === 'paypal') {
        // Handle PayPal payment logic
        // For simplicity, mark payment as successful
        $paymentSuccess = true;
    }
     if ($paymentSuccess) {
        // Check if the action is a reschedule
        $action = isset($_GET['action']) ? $_GET['action'] : null;

        if ($action === 'reschedule') {
            // Your logic to update the appointment in the database after a successful payment
            $updateQuery = $mysqli->prepare("UPDATE appointments SET appointment_date = ?, description = ? WHERE appointment_id = ?");
            $updateQuery->bind_param("sss", $newAppointmentDate, $newDescription, $appointmentId);

            if ($updateQuery->execute()) {
                $updateSuccessMessage = "Appointment rescheduled successfully!";
            } else {
                $updateErrorMessage = "Error updating appointment: " . $mysqli->error;
            }

            $updateQuery->close();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input, button, select {
            width: 100%;
            margin-bottom: 10px;
            padding: 8px;
            box-sizing: border-box;
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
    </style>
</head>
<body>
    <h1>Payment</h1>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($paymentSuccess)) {
        if ($paymentSuccess) {
            if (isset($bookingSuccessMessage)) {
                echo '<p class="success-message">' . $bookingSuccessMessage . '</p>';
            } 
            if (isset($updateSuccessMessage)) {
                echo '<p class="success-message">' . $updateSuccessMessage . '</p>';
            }
        } else {
            echo '<p class="error-message">Payment failed. Please try again.</p>';
        }
    }
    ?>

    <form action="payment.php" method="post">
        <input type="hidden" name="appointment_id" value="<?php echo $appointmentId; ?>">
        <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <form action="payment.php?action=reschedule" method="post">
    <input type="hidden" name="appointment_id" value="<?php echo $appointmentId; ?>">
    <input type="hidden" name="amount" value="<?php echo $amount; ?>">
        <label for="payment_option">Select Payment Option:</label>
        <select name="payment_option" required>
            <option value="payment_on_arrival">Payment on Arrival</option>
            <option value="paypal">PayPal</option>
        </select>

        <button type="submit">Make Payment</button>
    </form>
    
</form>
<?php include './config/footer.php' ?>  
</body>
</html>
