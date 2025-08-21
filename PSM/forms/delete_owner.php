<?php
require("connect2.php");

$id = $_POST['id'];

$sql = "DELETE FROM owner WHERE Oid=$id";
echo mysqli_query($conn, $sql) ? "Record deleted." : "Failed to delete.";
?>
