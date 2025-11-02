<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['cvmsaid']==0)) {
  header('location:logout.php');
  } else{


$did=$_GET['deleteid'];

$query=mysqli_query($con,"DELETE FROM tblvisitor where ID='$did'");

    if ($query) {
    header("Location: manage-newvisitors.php");
  }
  else
    {
      $msg="Something Went Wrong. Please try again";
    }


}

?>
