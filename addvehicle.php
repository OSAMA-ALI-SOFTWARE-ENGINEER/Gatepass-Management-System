<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (function_exists('mysqli_report')) {
  mysqli_report(MYSQLI_REPORT_OFF);
}
if (strlen($_SESSION['cvmsaid']==0)) {
  header('location:logout.php');
  } else{
    if(isset($_POST['submit']))
  {

$cvmsaid=$_SESSION['cvmsaid'];
 $fullname=$_POST['fullname'];
$vnumber=$_POST['vnumber'];
  // Ensure EnterDate, ExitDate columns exist for vehicles table
  $vehEnterDateCol = mysqli_query($con, "SHOW COLUMNS FROM vehicles LIKE 'EnterDate'");
  if ($vehEnterDateCol && mysqli_num_rows($vehEnterDateCol) == 0) {
    // Add EnterDate to capture creation time for vehicles (nullable to avoid backfilling old rows with now)
    @mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN EnterDate DATETIME NULL");
    // refresh column info
    $vehEnterDateCol = mysqli_query($con, "SHOW COLUMNS FROM vehicles LIKE 'EnterDate'");
  }
  @mysqli_query($con, "ALTER TABLE vehicles MODIFY EnterDate DATETIME NULL");
  $vehExitDateCol = mysqli_query($con, "SHOW COLUMNS FROM vehicles LIKE 'ExitDate'");
  if ($vehExitDateCol && mysqli_num_rows($vehExitDateCol) == 0) {
    @mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN ExitDate DATETIME NULL");
  }
  @mysqli_query($con, "ALTER TABLE vehicles MODIFY ExitDate DATETIME NULL");

 // Insert vehicle (set EnterDate explicitly if column exists)
 if ($vehEnterDateCol && mysqli_num_rows($vehEnterDateCol) > 0) {
   $query=mysqli_query($con,"insert into vehicles(fullname,vnumber,EnterDate) value('$fullname','$vnumber', NOW())");
 } else {
   $query=mysqli_query($con,"insert into vehicles(fullname,vnumber) value('$fullname','$vnumber')");
 }

    if ($query) {
    $msg="Vehicle Detail has been added.";
  }
  else
    {
      $msg="Something Went Wrong. Please try again";
    }


}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <!-- Title Page-->
    <title>Vehicle Form</title>

    <!-- Fontfaces CSS-->
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">

    <!-- Bootstrap CSS-->
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">

    <!-- Vendor CSS-->
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="css/theme.css" rel="stylesheet" media="all">

</head>

<body class="animsition">
    <div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <?php include_once('includes/sidebar.php');?>

        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <?php include_once('includes/header.php');?>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header" align='center'>
                                        <strong style="text-transform: uppercase; letter-spacing: 0.4px;">Add New Vehicle</strong>
                                    </div>
                                    <div class="card-body card-block">
                                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">


    <?php if($msg){
echo "<div class='alert alert-light' role='alert' style='font-size:16px; background: #000; color: #fff;margin-bottom: 18px;'> $msg</div>";
}  ?>


                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="text-input" class=" form-control-label">Owner Name</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="fullname" name="fullname" placeholder="Enter Full Name" class="form-control" required="">
                                                </div>
                                            </div>

                                          <!--  <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="email-input" class=" form-control-label">Email Input</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="email" id="email" name="email" placeholder="Enter Email" class="form-control" required="">

                                                </div>
                                            </div> -->


                                            <div class="row form-group">
                                                <div class="col col-md-3">
                                                    <label for="number" class=" form-control-label">Number Plate</label>
                                                </div>
                                                <div class="col-12 col-md-9">
                                                    <input type="text" id="vnumber" name="vnumber" placeholder="Enter Vehicle Registration Number" class="form-control" maxlength="10" required="">
                                                </div>
                                            </div>

                                        <p style="text-align: center;">
                                          <button type="submit" name="submit" id="submit" class="btn btn-success btn-block">Submit</button>
                                        </p>

                                    </div>
                                        </form>
                                    </div>

                                </div>

                        </div>

                    </div>


<?php include_once('includes/footer.php');?>
   </div> </div>
            </div>
        </div>
</div>
    <!-- Jquery JS-->
    <script src="vendor/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-4.1/popper.min.js"></script>
    <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/slick/slick.min.js">
    </script>
    <script src="vendor/wow/wow.min.js"></script>
    <script src="vendor/animsition/animsition.min.js"></script>
    <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js">
    </script>
    <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
    <script src="vendor/counter-up/jquery.counterup.min.js">
    </script>
    <script src="vendor/circle-progress/circle-progress.min.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="vendor/chartjs/Chart.bundle.min.js"></script>
    <script src="vendor/select2/select2.min.js">
    </script>

<script>



</script>

    <!-- Main JS-->
    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->
<?php }  ?>
