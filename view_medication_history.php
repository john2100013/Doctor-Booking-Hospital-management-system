<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "pms_db";

$conn = new mysqli($host, $user, $password, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$patient_name = isset($_GET['patient_name']) ? trim($_GET['patient_name']) : null;
if ($conn->error) {
    die("Error: " . $conn->error);
}
?>
<style>
      
      body {
          font-family: Arial, sans-serif;
          margin: 0;
          padding: 0;
          background-color: #f5f5f5;
          color: #333;
          background-image: url("/pms/img/hisory.png");
          background-repeat: no-repeat; /* Prevent background image from repeating */
        background-size: cover;
      }

      header {
          background-color: #4caf50;
          color: white;
          text-align: center;
          padding: 1px;
      }

      nav {
          text-align: center;
          background-color: #333;
          color: white;
          padding: 0px;
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
    <?php include 'sidebar.php'; ?>

    <h1>View Medication History</h1>

    <form method="get" action="">
        <label for="patient_name"><b><big>Enter your Name:</big></b></label>
        <input type="text" name="patient_name" id="patient_name" required>
        <input type="submit" value="View History">
    </form>

    <?php
    if ($patient_name !== null) {
        // Convert the patient name to lowercase for case-insensitive comparison
        $patient_name_lower = strtolower($patient_name);
        if ($conn->error) {
            die("Error: " . $conn->error);
        }
        $stmt = $conn->prepare("SELECT pmh.*, m.medicine_name
                                FROM patient_medication_history pmh
                                JOIN medicines m ON pmh.medicine_details_id = m.id
                                WHERE pmh.patient_visit_id = (
                                    SELECT id
                                    FROM patients
                                    WHERE LOWER(patient_name) = LOWER(?)
                                )");
                                if ($conn->error) {
                                    die("Error: " . $conn->error);
                                }
        $stmt->bind_param("s", $patient_name_lower);
        if ($conn->error) {
            die("Error: " . $conn->error);
        }
        $stmt->execute();

        if ($stmt->error) {
            die("Error executing statement: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h2>Medication History for Patient $patient_name</h2>";
            echo "<table>
                    <tr>
                        <th>ID</th>
                        <th>Medicine Name</th>
                        <th>Quantity</th>
                        <th>Dosage</th>
                    </tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['medicine_name']}</td>
                        <td>{$row['quantity']}</td>
                        <td>{$row['dosage']}</td>
                      </tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No medication history found for Patient $patient_name.</p>";
        }

        $stmt->close();
    }
    ?>

    <?php include 'footer.php'; ?>
    <?php $conn->close(); ?>
    
</body>
</html>
