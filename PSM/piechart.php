<?php
// db connection
$conn = new mysqli("localhost", "root", "", "pet clinic");

// Count Data
$ownerCount = $conn->query("SELECT COUNT(*) AS total FROM owner")->fetch_assoc()['total'];
$petCount = $conn->query("SELECT COUNT(*) AS total FROM pet")->fetch_assoc()['total'];
$doctorCount = $conn->query("SELECT COUNT(*) AS total FROM doctor")->fetch_assoc()['total'];
$appointmentCount = $conn->query("SELECT COUNT(*) AS total FROM appointment")->fetch_assoc()['total'];
$paymentCount = $conn->query("SELECT COUNT(*) AS total FROM payment")->fetch_assoc()['total'];

// Pets distribution by type
$petTypeData = $conn->query("SELECT Pspecies, COUNT(*) as total FROM pet GROUP BY Pspecies");
$petLabels = [];
$petValues = [];
while ($row = $petTypeData->fetch_assoc()) {
    $petLabels[] = $row['Pspecies'];
    $petValues[] = $row['total'];
}

// Appointments per doctor
$doctorData = $conn->query("SELECT d.Dname, COUNT(*) as total FROM appointment a 
                            JOIN doctor d ON a.Did = d.Did 
                            GROUP BY d.Dname");
$doctorLabels = [];
$doctorValues = [];
while ($row = $doctorData->fetch_assoc()) {
    $doctorLabels[] = $row['Dname'];
    $doctorValues[] = $row['total'];
}

// Payments by status
$paymentData = $conn->query("SELECT Pstatus, SUM(Pamount) as total 
                             FROM payment 
                             GROUP BY Pstatus");
$paymentLabels = [];
$paymentValues = [];
while ($row = $paymentData->fetch_assoc()) {
    $paymentLabels[] = $row['Pstatus'];
    $paymentValues[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pet Clinic Dashboard</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <style>
    .dashboard-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      border-radius: 15px;
      flex: 1; /* equal width */
      min-width: 180px; /* prevents too small */
    }
    .dashboard-card:hover {
      transform: translateY(-5px) scale(1.03);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }
    .dashboard-card .card-body h5 {
      font-size: 1.2rem;
      margin-bottom: 10px;
    }
    .dashboard-card .card-body h2 {
      font-weight: bold;
    }

    /* Flex layout for summary cards */
    .dashboard-row {
      display: flex;
      justify-content: space-between;
      gap: 15px;
      flex-wrap: nowrap; /* keep in one line */
    }
    a.card-link {
      flex: 1;
      text-decoration: none;
      color: inherit;
    }
  </style>
</head>
<body class="bg-light p-4">

<div class="container">
  
  <!-- Summary Cards with Icons -->
  <div class="dashboard-row mb-5">
    
    <!-- Owners -->
    <a href="forms/owner.php" class="card-link">
      <div class="card text-white shadow dashboard-card" style="background: linear-gradient(135deg, #4e54c8, #8f94fb);">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5>Owners</h5>
            <h2><?= $ownerCount ?></h2>
          </div>
          <i class="fas fa-user-tie fa-3x"></i>
        </div>
      </div>
    </a>

    <!-- Pets -->
    <a href="forms/pet.php" class="card-link">
      <div class="card text-white shadow dashboard-card" style="background: linear-gradient(135deg, #56ab2f, #a8e063);">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5>Pets</h5>
            <h2><?= $petCount ?></h2>
          </div>
          <i class="fas fa-dog fa-3x"></i>
        </div>
      </div>
    </a>

    <!-- Doctors -->
    <a href="forms/doctor.php" class="card-link">
      <div class="card text-white shadow dashboard-card" style="background: linear-gradient(135deg, #ff512f, #dd2476);">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5>Doctors</h5>
            <h2><?= $doctorCount ?></h2>
          </div>
          <i class="fas fa-user-md fa-3x"></i>
        </div>
      </div>
    </a>

    <!-- Appointments -->
    <a href="forms/appointment.php" class="card-link">
      <div class="card text-white shadow dashboard-card" style="background: linear-gradient(135deg, #1f4037, #99f2c8);">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5>Appointments</h5>
            <h2><?= $appointmentCount ?></h2>
          </div>
          <i class="fas fa-calendar-check fa-3x"></i>
        </div>
      </div>
    </a>

    <!-- Payments -->
    <a href="forms/payment.php" class="card-link">
      <div class="card text-white shadow dashboard-card" style="background: linear-gradient(135deg, #ff9966, #ff5e62);">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5>Payments</h5>
            <h2><?= $paymentCount ?></h2>
          </div>
          <i class="fas fa-credit-card fa-3x"></i>
        </div>
      </div>
    </a>
  </div>

  <!-- Charts -->
  <div class="row">
    <div class="col-md-6 mb-4" style="border:white 1px ; background-color:white; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
      <canvas id="petChart"></canvas>
    </div>
    <div class="col">
      <div class="col-md-12 mb-4" style="border:white 1px ; background-color:white; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
        <canvas id="doctorChart"></canvas>
      </div>
      <div class="col-md-12 mb-4" style="border:white 1px ; background-color:white; box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);">
        <canvas id="paymentChart"></canvas>
      </div>
    </div>
  </div>
</div>

<script>
  // Pet Type Pie Chart
  new Chart(document.getElementById("petChart"), {
    type: "pie",
    data: {
      labels: <?= json_encode($petLabels) ?>,
      datasets: [{
        data: <?= json_encode($petValues) ?>,
        backgroundColor: ["#FF6384","#36A2EB","#FFCE56","#4BC0C0","#9966FF"]
      }]
    }
  });

  // Doctor-wise Appointments Bar Chart
  new Chart(document.getElementById("doctorChart"), {
    type: "bar",
    data: {
      labels: <?= json_encode($doctorLabels) ?>,
      datasets: [{
        label: "Appointments",
        data: <?= json_encode($doctorValues) ?>,
        backgroundColor: "#36A2EB"
      }]
    }
  });

  // Payments by Status Line Chart
  new Chart(document.getElementById("paymentChart"), {
    type: "line",
    data: {
      labels: <?= json_encode($paymentLabels) ?>,
      datasets: [{
        label: "Payments",
        data: <?= json_encode($paymentValues) ?>,
        borderColor: "#FF6384",
        fill: false,
        tension: 0.3
      }]
    }
  });
</script>

</body>
</html>
