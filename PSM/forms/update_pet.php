<?php
require("connect2.php");

$id = $_POST['id'];  // original Pid used for WHERE clause
$Pname = $_POST['Pname'];
$Pbreed = $_POST['Pbreed'];
$Pspecies = $_POST['Pspecies'];
$Pgender = $_POST['Pgender'];
$Oid = $_POST['Oid'];

$sql = "UPDATE pet SET 
          Pname = '$Pname', 
          Pbreed = '$Pbreed', 
          Pspecies = '$Pspecies',
          Pgender = '$Pgender',
          Oid = '$Oid'
        WHERE Pid = '$id'";

echo mysqli_query($conn, $sql) ? "Record updated." : "Failed to update.";
?>
