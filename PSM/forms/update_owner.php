<?php
require("connect2.php");

$id = $_POST['id'];
$name = $_POST['Oname'];
$phone = $_POST['Ophoneno'];
$email = $_POST['Oemail'];
$address = $_POST['Oaddress'];

$sql = "UPDATE owner SET 
          Oname='$name', 
          Ophoneno='$phone', 
          Oemail='$email', 
          Oaddress='$address' 
        WHERE Oid=$id";

echo mysqli_query($conn, $sql) ? "Record updated." : "Failed to update.";
?>
