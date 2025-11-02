<?php
include 'includes/dbconnection.php';

$id = $_GET['id'];

  $sql = "DELETE FROM designations WHERE id=$id";
  if (mysqli_query($con, $sql)) {
    header("Location: designations.php");
  } else {
    echo "Error deleting rows: " . mysqli_error($conn);
  }

?>
