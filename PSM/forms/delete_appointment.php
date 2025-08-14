<?php
require("connect2.php");

$id = $_POST['id'];

$sql = "DELETE FROM appointment WHERE Aid = '$id'";

echo mysqli_query($conn, $sql) ? "Appointment deleted." : "Failed to delete appointment.";
?>
