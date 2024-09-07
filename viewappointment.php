<?php
include "./config/database.php";

// Check if the form is submitted for rescheduling or canceling an appointment
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = isset($_POST['action']) ? $_POST['action'] : null;
    $appointmentId = isset($_POST['appointment_id']) ? $_POST['appointment_id'] : null;

    if ($action === 'reschedule') {
        // Check if the user agrees to pay a penalty
        $agreeToPenalty = isset($_POST['agree_to_penalty']) ? $_POST['agree_to_penalty'] : false;

        if ($agreeToPenalty) {
            // Proceed with rescheduling
            $newAppointmentDate = isset($_POST['new_appointment_date']) ? $_POST['new_appointment_date'] : null;
            $newDescription = isset($_POST['new_description']) ? $_POST['new_description'] : null;

            if ($newAppointmentDate && $newDescription) {
                // Update the appointment with new information
                $updateQuery = $mysqli->prepare("UPDATE appointments SET appointment_date = ?, description = ? WHERE appointment_id = ?");
                $updateQuery->bind_param("sss", $newAppointmentDate, $newDescription, $appointmentId);

                if ($updateQuery->execute()) {
                    $updateSuccessMessage = "Appointment rescheduled successfully!";
                } else {
                    $updateErrorMessage = "Error rescheduling appointment: " . $mysqli->error;
                }

                $updateQuery->close();
            } else {
                $updateErrorMessage = "Please provide both new appointment date and description for rescheduling.";
            }
        } else {
            // Inform the user about the penalty and provide an option to go back
            $penaltyMessage = "Notice: By rescheduling, a penalty of 200 shillings will be charged. Do you wish to continue?";
        }
    } elseif ($action === 'cancel') {
        // Your code for canceling an appointment goes here
        // Implement the logic to delete the appointment with $appointmentId from the database
        $deleteQuery = $mysqli->prepare("DELETE FROM appointments WHERE appointment_id = ?");
        $deleteQuery->bind_param("s", $appointmentId);

        if ($deleteQuery->execute()) {
            $cancelSuccessMessage = "Appointment canceled successfully!";
        } else {
            $cancelErrorMessage = "Error canceling appointment: " . $mysqli->error;
        }

        $deleteQuery->close();
    }
}

// Get appointments for the specified patient
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputPatientId = filter_input(INPUT_POST, 'patientid', FILTER_SANITIZE_STRING);
    $appointments = getAppointmentsForPatient($inputPatientId);
}

function getAppointmentsForPatient($patientId) {
    $mysqli = require __DIR__ . "/config/database.php";

    // Adjust the SQL query based on your database schema
    $query = "SELECT * FROM appointments WHERE patientid = ? ORDER BY appointment_id DESC";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $patientId);
    $stmt->execute();

    $result = $stmt->get_result();

    // Fetch appointments into an array
    $appointments = [];
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }

    $stmt->close();
    $mysqli->close();

    return $appointments;
}
function removePastAppointments($appointments) {
    $currentDateTime = new DateTime();

    foreach ($appointments as $key => $appointment) {
        $appointmentDateTime = new DateTime($appointment['appointment_date']);

        // Remove appointments with dates in the past
        if ($currentDateTime > $appointmentDateTime) {
            unset($appointments[$key]);
        }
    }

    return $appointments;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
    
    <style>
      
      body {
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
          background-color: #f5f5f5;
          color: #333;
          background-image: url("/pms/img/Hospital.png");
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
<?php include 'header.php'; ?>
    

    <nav>
        <a href="apointment.php">Book Appointment</a>
        <a href="viewappointment.php">View Appointment</a>
        <a href="view_medication_history.php">Prescription</a>
        
        <a href="logout.php">Logout</a>
    </nav>

    <h1>Your Appointments</h1>

    <form action="viewappointment.php" method="post">
        <label for="patient_id">Enter Your Patient ID:</label>
        <input type="text" name="patientid" required>
        <button type="submit">View Appointments</button>
    </form>

    <?php if (!empty($appointments)): ?>
        <?php if (isset($updateSuccessMessage)): ?>
            <p class="success-message"><?php echo $updateSuccessMessage; ?></p>
        <?php elseif (isset($updateErrorMessage)): ?>
            <p class="error-message"><?php echo $updateErrorMessage; ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Appointment Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo $appointment['appointment_id']; ?></td>
                        <td><?php echo $appointment['patientname']; ?></td>
                        <td><?php echo $appointment['appointment_date']; ?></td>
                        <td><?php echo $appointment['description']; ?></td>
                        <td>
                            <form action="viewappointment.php" method="post">
                                <input type="hidden" name="action" value="reschedule">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                                <label for="new_appointment_date">New Appointment Date:</label>
                                <input type="datetime-local" name="new_appointment_date" required min="<?php echo date('Y-m-d\TH:i'); ?>">
                                <label for="new_description">New Description:</label>
                                <textarea name="new_description" rows="1" required></textarea>
                                <label for="agree_to_penalty">
                                    <input type="checkbox" name="agree_to_penalty" required>
                                    I AGREE TO ALL THE TERMS AND CONDITIONS .
                                </label>
                                <button type="submit">Reschedule</button>
                            </form>
                            <form action="viewappointment.php" method="post">
                                <input type="hidden" name="action" value="cancel">
                                <input type="hidden" name="appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                                <button type="submit">Cancel</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php if (isset($penaltyMessage)): ?>
            <p class="penalty-message"><?php echo $penaltyMessage; ?></p>
        <?php endif; ?>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST"): ?>
        <p class="error-message">No appointments found for the specified patient ID.</p>
    <?php endif; ?>

    <?php include 'footer.php'; ?>
</body>
</html>
