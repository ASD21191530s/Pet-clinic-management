<?php
require("connect2.php");

$id = $_POST['id'];                  // Must match the sent key
$Dname = $_POST['Dname'];
$Demail = $_POST['Demail'];
$Dspecialization = $_POST['Dspecialization'];
$Dphone = $_POST['Dphoneno'];          // Must match the real column name in DB

$sql = "UPDATE doctor SET 
          Dname = '$Dname', 
          Demail = '$Demail', 
          Dspecialization = '$Dspecialization',
          Dphoneno = '$Dphone'
        WHERE Did = '$id'";

echo mysqli_query($conn, $sql) ? "Doctor updated." : "Failed to update doctor.";
?>
