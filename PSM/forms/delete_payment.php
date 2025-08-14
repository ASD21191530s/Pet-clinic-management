<?php
require("connect2.php");

$id = $_POST['id'];

$sql = "DELETE FROM payment WHERE pay_id = '$id'";

echo mysqli_query($conn, $sql) ? "Payment deleted." : "Failed to delete payment.";
?>
