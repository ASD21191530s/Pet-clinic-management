<?php
require("connect2.php");

$id = $_POST['id'];
$Aid=$_POST['Aid'];
$Pamount = $_POST['Pamount'];
$Pmethode = $_POST['Pmethode'];
$Pstatus = $_POST['Pstatus'];

$sql = "UPDATE payment SET 
          Aid='$Aid',
          Pamount = '$Pamount', 
          Pmethode = '$Pmethode', 
          Pstatus = '$Pstatus'
        WHERE pay_id = '$id'";

echo mysqli_query($conn, $sql) ? "Payment updated." : "Failed to update payment.";
?>
