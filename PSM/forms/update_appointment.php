<?php
require("connect2.php");

$id = $_POST['id'];
$Pid = $_POST['Pid'];
$Did = $_POST['Did'];
$Reason = $_POST['Reason'];
$Date = $_POST['Date'];
$Status=$_POST['Status'];
$sql = "UPDATE appointment SET 
          Pid = '$Pid', 
          Did = '$Did', 
          Reason = '$Reason', 
          Date = '$Date',
          Status='$Status'        
        WHERE Aid = '$id'";

echo mysqli_query($conn, $sql) ? "Appointment updated." : "Failed to update appointment.";
?>
