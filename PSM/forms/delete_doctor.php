<?php
require("connect2.php");

$id = $_POST['id'];

// Delete related appointments first to avoid FK error
mysqli_query($conn, "DELETE FROM appointment WHERE Did = $id");

// Then delete doctor
$sql = "DELETE FROM doctor WHERE Did = '$id'";
echo mysqli_query($conn, $sql) ? "Doctor deleted." : "Failed to delete doctor.";
?>
