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

$eid=$_GET['editid'];

$remark=$_POST['remark'];
 $query=mysqli_query($con,"update tblvisitor set remark='$remark' where  ID='$eid'");


    if ($query) {
    $msg="Visitors Remark has been Updated.";
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
    <title>CVSM Visitors Forms</title>

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
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->

        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <?php include_once('includes/header.php');?>
            <!-- HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">

                            <div class="col-xl-10 offset-xl-1 col-lg-12">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-light py-3">
                                        <h4 class="mb-0 text-uppercase text-center" style="letter-spacing:0.5px;">Visitor Details</h4>
                                    </div>
                                    <div class="card-body">
                                        <?php if($msg){ ?>
                                            <div class="alert alert-success text-center" role="alert"><?php echo htmlspecialchars($msg); ?></div>
                                        <?php } ?>
                                        <?php
                                            $eid=$_GET['editid'];
                                            $ret=mysqli_query($con,"select * from tblvisitor where ID='$eid'");
                                            if ($row=mysqli_fetch_array($ret)) {
                                                $fullName = $row['FullName'] ?? '-';
                                                $cnic = $row['CNIC'] ?? '-';
                                                $mobile = $row['MobileNumber'] ?? '-';
                                                $dept = $row['Deptartment'] ?? '-';
                                                $address = $row['Address'] ?? '-';
                                                $enter = !empty($row['EnterDate']) ? date('Y-m-d H:i', strtotime($row['EnterDate'])) : '-';
                                                $exit = !empty($row['ExitDate']) ? date('Y-m-d H:i', strtotime($row['ExitDate'])) : '-';
                                                $entryStatusBadge = '<span class="badge badge-pill '.($exit === '-' ? 'badge-success' : 'badge-secondary').'">'.($exit === '-' ? 'IN CAMPUS' : 'EXITED').'</span>';
                                        ?>
                                        <div class="row align-items-center">
                                            <div class="col-md-4 text-center mb-4 mb-md-0">
                                                <div class="avatar avatar-xl mb-3">
                                                    <span class="avatar-initial rounded-circle bg-primary text-white" style="display:inline-flex;width:96px;height:96px;align-items:center;justify-content:center;font-size:36px;">
                                                        <?php echo strtoupper(substr($fullName,0,1)); ?>
                                                    </span>
                                                </div>
                                                <h5 class="mb-1"><?php echo htmlspecialchars($fullName); ?></h5>
                                                <div><?php echo $entryStatusBadge; ?></div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="table-responsive">
                                                    <table class="table table-borderless table-sm mb-0">
                                                        <tbody>
                                                            <tr>
                                                                <th class="text-muted" style="width:35%;">CNIC</th>
                                                                <td><?php echo htmlspecialchars($cnic); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Mobile Number</th>
                                                                <td><?php echo htmlspecialchars($mobile); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Department</th>
                                                                <td><?php echo htmlspecialchars($dept); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Address</th>
                                                                <td><?php echo htmlspecialchars($address); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Visitor Enter Time</th>
                                                                <td><?php echo $enter; ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th class="text-muted">Visitor Exit Time</th>
                                                                <td class="<?php echo $exit === '-' ? 'text-muted' : 'text-danger font-weight-bold'; ?>"><?php echo $exit; ?></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <h6 class="text-uppercase text-muted mb-2">Quick Actions</h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a href="manage-newvisitors.php" class="btn btn-outline-secondary btn-sm mr-2 mb-2"><i class="fa fa-arrow-left mr-1"></i>Back to List</a>
                                                    <a href="visitor-enter.php?id=<?php echo $row['ID']; ?>" class="btn btn-outline-primary btn-sm mr-2 mb-2"><i class="fa fa-sign-in mr-1"></i>Mark Enter Now</a>
                                                    <a href="visitor-exit.php?id=<?php echo $row['ID']; ?>" class="btn btn-outline-danger btn-sm mb-2"><i class="fa fa-sign-out mr-1"></i>Mark Exit Now</a>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <h6 class="text-uppercase text-muted mb-2">Notes</h6>
                                                <form method="post">
                                                    <div class="form-group mb-2">
                                                        <textarea name="remark" rows="3" class="form-control" placeholder="Add or update remarks..."><?php echo htmlspecialchars($row['remark'] ?? ''); ?></textarea>
                                                    </div>
                                                    <button type="submit" name="submit" class="btn btn-success btn-sm">Save Remark</button>
                                                </form>
                                            </div>
                                        </div>
                                        <?php } else { ?>
                                            <div class="alert alert-warning text-center mb-0">Visitor record not found.</div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            </div>

<?php include_once('includes/footer.php');?>
                </div>
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

    <!-- Main JS-->
    <script src="js/main.js"></script>

</body>

</html>
<!-- end document-->
<?php } ?>
