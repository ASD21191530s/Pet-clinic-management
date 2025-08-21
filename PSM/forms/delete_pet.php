<?php
require("connect2.php");

$id = $_POST['id'];

// Step 1: Delete appointments related to this pet
mysqli_query($conn, "DELETE FROM appointment WHERE Pid = $id");

// Step 2: Now delete the pet
$sql = "DELETE FROM pet WHERE Pid = $id";
echo mysqli_query($conn, $sql) ? "Record deleted." : "Failed to delete.";
?>