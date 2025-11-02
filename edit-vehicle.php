<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (strlen($_SESSION['cvmsaid']==0)) {
  header('location:logout.php');
  exit();
}

// Ensure column existence to avoid runtime errors
@mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN EnterDate DATETIME NULL");
@mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN ExitDate DATETIME NULL");

$msg = '';
// Resolve vehicle id
$id = isset($_GET['id']) ? intval($_GET['id']) : (isset($_POST['id']) ? intval($_POST['id']) : 0);
if ($id <= 0) {
  header('Location: managevehicle.php');
  exit();
}

if (isset($_POST['update'])) {
  $fullname = trim($_POST['fullname']);
  $vnumber = trim($_POST['vnumber']);
  $enterRaw = isset($_POST['enterdate']) ? trim($_POST['enterdate']) : '';
  $exitRaw  = isset($_POST['exitdate']) ? trim($_POST['exitdate']) : '';

  $enterDate = $enterRaw !== '' ? date('Y-m-d H:i:s', strtotime($enterRaw)) : NULL;
  $exitDate  = $exitRaw  !== '' ? date('Y-m-d H:i:s', strtotime($exitRaw))  : NULL;

  $setEnter = is_null($enterDate) ? "EnterDate = NULL" : "EnterDate = '".mysqli_real_escape_string($con, $enterDate)."'";
  $setExit  = is_null($exitDate)  ? "ExitDate = NULL"  : "ExitDate = '".mysqli_real_escape_string($con, $exitDate)."'";

  $fullnameEsc = mysqli_real_escape_string($con, $fullname);
  $vnumberEsc  = mysqli_real_escape_string($con, $vnumber);

  $query = mysqli_query($con,
    "UPDATE vehicles SET fullname='$fullnameEsc', vnumber='$vnumberEsc', $setEnter, $setExit WHERE id=$id");
  if ($query) {
    $msg = 'Vehicle updated successfully';
  } else {
    $msg = 'Update failed. Please try again';
  }
}

// Fetch current row
$ret = mysqli_query($con, "SELECT * FROM vehicles WHERE id=$id LIMIT 1");
$row = mysqli_fetch_assoc($ret);
if (!$row) {
  header('Location: managevehicle.php');
  exit();
}

$enterValue = !empty($row['EnterDate']) ? date('Y-m-d\TH:i', strtotime($row['EnterDate'])) : '';
$exitValue  = !empty($row['ExitDate'])  ? date('Y-m-d\TH:i', strtotime($row['ExitDate']))  : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Edit Vehicle</title>
    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
    <link href="vendor/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-4.1/bootstrap.min.css" rel="stylesheet" media="all">
    <link href="vendor/animsition/animsition.min.css" rel="stylesheet" media="all">
    <link href="vendor/bootstrap-progressbar/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet" media="all">
    <link href="vendor/wow/animate.css" rel="stylesheet" media="all">
    <link href="vendor/css-hamburgers/hamburgers.min.css" rel="stylesheet" media="all">
    <link href="vendor/slick/slick.css" rel="stylesheet" media="all">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="all">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
</head>
<body class="animsition">
  <div class="page-wrapper">
    <?php include_once('includes/sidebar.php');?>
    <div class="page-container">
      <?php include_once('includes/header.php');?>
      <div class="main-content">
        <div class="section__content section__content--p30">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-8 offset-lg-2">
                <div class="card">
                  <div class="card-header" align='center'>
                    <strong style="text-transform: uppercase; letter-spacing: 0.4px;">Edit Vehicle</strong>
                  </div>
                  <div class="card-body card-block">
                    <?php if($msg){ echo "<div class='alert alert-info' role='alert' style='font-size:16px;'>".htmlspecialchars($msg)."</div>"; } ?>
                    <form action="" method="post" class="form-horizontal">
                      <input type="hidden" name="id" value="<?php echo $id; ?>">
                      <div class="row form-group">
                        <div class="col col-md-3">
                          <label class="form-control-label">Owner Name</label>
                        </div>
                        <div class="col-12 col-md-9">
                          <input type="text" name="fullname" class="form-control" value="<?php echo htmlspecialchars($row['fullname']); ?>" required>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col col-md-3">
                          <label class="form-control-label">Number Plate</label>
                        </div>
                        <div class="col-12 col-md-9">
                          <input type="text" name="vnumber" class="form-control" value="<?php echo htmlspecialchars($row['vnumber']); ?>" maxlength="10" required>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col col-md-3">
                          <label class="form-control-label">Enter Time</label>
                        </div>
                        <div class="col-12 col-md-9">
                          <input type="datetime-local" name="enterdate" class="form-control" value="<?php echo $enterValue; ?>">
                          <small class="form-text text-muted">Leave blank to keep empty.</small>
                        </div>
                      </div>
                      <div class="row form-group">
                        <div class="col col-md-3">
                          <label class="form-control-label">Exit Time</label>
                        </div>
                        <div class="col-12 col-md-9">
                          <input type="datetime-local" name="exitdate" class="form-control" value="<?php echo $exitValue; ?>">
                          <small class="form-text text-muted">Leave blank to keep empty.</small>
                        </div>
                      </div>
                      <div class="text-center">
                        <button type="submit" name="update" class="btn btn-success">Update</button>
                        <a href="managevehicle.php" class="btn btn-secondary">Back</a>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Jquery JS-->
  <script src="vendor/jquery-3.2.1.min.js"></script>
  <script src="vendor/bootstrap-4.1/popper.min.js"></script>
  <script src="vendor/bootstrap-4.1/bootstrap.min.js"></script>
  <script src="vendor/animsition/animsition.min.js"></script>
  <script src="vendor/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <script src="vendor/counter-up/jquery.waypoints.min.js"></script>
  <script src="vendor/counter-up/jquery.counterup.min.js"></script>
  <script src="vendor/circle-progress/circle-progress.min.js"></script>
  <script src="vendor/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="vendor/chartjs/Chart.bundle.min.js"></script>
  <script src="vendor/select2/select2.min.js"></script>
  <script src="js/main.js"></script>
</body>
</html>
<?php // end ?>

