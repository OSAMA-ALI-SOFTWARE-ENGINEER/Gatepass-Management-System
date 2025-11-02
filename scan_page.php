<!DOCTYPE html>
<html>

<head>
  <title>QR Scanner</title>
  <script src="https://unpkg.com/html5-qrcode"></script>
  <link href="css/font-face.css" rel="stylesheet" media="all">
  <link href="css/theme.css" rel="stylesheet" media="all">
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

<body>
  <h2>Scan Visitor QR Code</h2>
  <div id="reader" style="width:300px;"></div>
  <div id="result"></div>

  <script>
    function onScanSuccess(decodedText) {
      // AJAX call to fetch_data.php using the decoded QR code ID
      fetch("fetch_data.php?id=" + decodedText)
        .then(res => res.json())
        .then(data => {
          if (data) {
            document.getElementById("result").innerHTML = `
<p><b>Name:</b> ${data.FullName}</p>
 <p><b>Roll No:</b> ${data.rollno}</p>
<p><b>Department:</b> ${data.Deptartment}</p>
<img src="${data.photo}" width="100" height="100">
`;
          } else {
            document.getElementById("result").innerHTML = "No record found.";
          }
        });
    }

    const html5QrcodeScanner = new Html5QrcodeScanner(
      "reader", {
        fps: 10,
        qrbox: 250
      });
    html5QrcodeScanner.render(onScanSuccess);
  </script>
</body>

</html>
<?php
session_start();
error_reporting(0);
// Ensure includes/dbconnection.php exists and has database connection details
include('includes/dbconnection.php');

if (strlen($_SESSION['cvmsaid'] == 0)) {
  header('location:logout.php');
} else {

?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>CVMS Visitors</title>

    <link href="css/font-face.css" rel="stylesheet" media="all">
    <link href="css/theme.css" rel="stylesheet" media="all">
  </head>

  <body class="animsition">
    <div class="page-wrapper">
      <?php include_once('includes/sidebar.php'); ?>
      <div class="page-container">
        <?php include_once('includes/header.php'); ?>
        <div class="main-content">
          <div class="section__content section__content--p30">
            <div class="container-fluid">
              <div class="row">
                <div class="col-lg-12">
                  <div class="table-responsive table--no-card m-b-30">
                    <?php
                    if (isset($_POST['search'])) {
                      // Search Logic Starts Here
                      $sdata = $_POST['searchdata'];
                    ?>
                      <h4 align="center">Result against "<?php echo $sdata; ?>" keyword </h4>
                      <hr />
                      <table class="table table-borderless table-striped table-earning">
                        <thead>
                          <tr>
                            <th>S.NO</th>
                            <th>Full Name</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <?php
                        $ret = mysqli_query($con, "select *from tblvisitor where FullName like '$sdata%'||MobileNumber like '$sdata%'");
                        $num = mysqli_num_rows($ret);
                        if ($num > 0) {
                          $cnt = 1;
                          while ($row = mysqli_fetch_array($ret)) {

                        ?>
                            <tr>
                              <td><?php echo $cnt; ?></td>
                              <td><?php echo $row['FullName']; ?></td>
                              <td><?php echo $row['MobileNumber']; ?></td>
                              <td><?php echo $row['Email']; ?></td>
                              <td><a href="visitor-detail.php?editid=<?php echo $row['ID']; ?>"><i class="fa fa-edit fa-1x"></i></a></a></td>
                            </tr>
                          <?php
                            $cnt = $cnt + 1;
                          }
                        } else { ?>
                          <tr>
                            <td colspan="8"> No record found against this search</td>
                          </tr>

                      <?php }
                      } ?>

                      </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php include_once('includes/footer.php'); ?>
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
<?php } ?>