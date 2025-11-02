<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (function_exists('mysqli_report')) {
    mysqli_report(MYSQLI_REPORT_OFF);
}
error_reporting(0);
if (strlen($_SESSION['cvmsaid'] == 0)) {
    header('location:logout.php');
} else { ?>
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
        <title>Dashboard</title>

        <!-- Fontfaces CSS-->
        <link href="css/font-face.css" rel="stylesheet" media="all">
        <link href="vendor/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
        <link href="vendor/font-awesome-5/css/fontawesome-all.min.css" rel="stylesheet" media="all">
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


            <!-- MENU SIDEBAR-->
            <?php include_once('includes/sidebar.php'); ?>
            <!-- END MENU SIDEBAR-->

            <!-- PAGE CONTAINER-->
            <div class="page-container">
                <!-- HEADER DESKTOP-->
                <?php include_once('includes/header.php'); ?>
                <!-- HEADER DESKTOP-->

                <!-- MAIN CONTENT-->
                <div class="main-content">
                    <div class="section__content section__content--p30">
                        <div class="container-fluid">
                            <?php
                            //Total Visitors visitors
                            $query3 = mysqli_query($con, "select ID from tblvisitor");
                            $count_total_visitors = mysqli_num_rows($query3);
                            ?>
                            <div class="row m-t-25">
                                <div class="col-sm-6 col-lg-4">
                                    <div class="overview-item overview-item--c3">
                                        <div class="overview__inner">
                                            <div class="overview-box clearfix">

                                                <div class="text" style="margin-bottom: 25px; margin-top: 10px;">
                                                    <h2><?php echo $count_total_visitors ?></h2>
                                                    <span style="color: #fddd;">Total Visitors</span>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <?php
                                //todays visitors
                                $query = mysqli_query($con, "select ID from tblvisitor where date(EnterDate)=CURDATE();");
                                $count_today_visitors = mysqli_num_rows($query);
                                ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="overview-item overview-item--c3">
                                        <div class="overview__inner">
                                            <div class="overview-box clearfix">

                                                <div class="text" style="margin-bottom: 25px; margin-top: 10px;">
                                                    <h2><?php echo $count_today_visitors; ?></h2>
                                                    <span style="color: #fddd;">Today Visitors</span>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>


                                <?php
                                //Yesterdays visitors
                                $query1 = mysqli_query($con, "select ID from tblvisitor where date(EnterDate)=CURDATE()-1;");
                                $count_yesterday_visitors = mysqli_num_rows($query1);
                                ?>
                                <div class="col-sm-6 col-lg-4">
                                    <div class="overview-item overview-item--c3">
                                        <div class="overview__inner">
                                            <div class="overview-box clearfix">

                                                <div class="text" style="margin-bottom: 25px; margin-top: 10px;">
                                                    <h2><?php echo $count_yesterday_visitors ?></h2>
                                                    <span style="color: #fddd;">Yesterday Visitors</span>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">

                            <?php
                           //Total vehicle
                            $query1=mysqli_query($con,"select ID from vehicles");
                           $count_total_vehicles=mysqli_num_rows($query1);
                            ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="overview-item overview-item--c3">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">

                                            <div class="text" style="margin-bottom: 25px; margin-top: 10px;">
                                                <h2><?php echo $count_total_vehicles?></h2>
                                                <span style="color: #fddd;">Total Vehicles</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                // today's vehicles (ensure column exists; then count)
                                $count_today_vehicles = 0;
                                $vehEnterDateCol = mysqli_query($con, "SHOW COLUMNS FROM vehicles LIKE 'EnterDate'");
                                if ($vehEnterDateCol && mysqli_num_rows($vehEnterDateCol) == 0) {
                                    @mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN EnterDate DATETIME NULL");
                                    $vehEnterDateCol = mysqli_query($con, "SHOW COLUMNS FROM vehicles LIKE 'EnterDate'");
                                }
                                @mysqli_query($con, "ALTER TABLE vehicles MODIFY EnterDate DATETIME NULL");
                                if ($vehEnterDateCol && mysqli_num_rows($vehEnterDateCol) > 0) {
                                    $query2 = mysqli_query($con, "SELECT ID FROM vehicles WHERE DATE(EnterDate)=CURDATE();");
                                    if ($query2) {
                                        $count_today_vehicles = mysqli_num_rows($query2);
                                    }
                                }
                            ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="overview-item overview-item--c3">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">

                                            <div class="text" style="margin-bottom: 25px; margin-top: 10px;">
                                                <h2><?php echo $count_today_vehicles?></h2>
                                                <span style="color: #fddd;">Today's Vehicles</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                                // yesterday's vehicles (reuse column check above)
                                $count_yesterday_vehicles = 0;
                                if ($vehEnterDateCol && mysqli_num_rows($vehEnterDateCol) > 0) {
                                    $query3 = mysqli_query($con, "SELECT ID FROM vehicles WHERE DATE(EnterDate)=CURDATE()-1;");
                                    if ($query3) {
                                        $count_yesterday_vehicles = mysqli_num_rows($query3);
                                    }
                                }
                            ?>
                            <div class="col-sm-6 col-lg-4">
                                <div class="overview-item overview-item--c3">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">

                                            <div class="text" style="margin-bottom: 25px; margin-top: 10px;">
                                                <h2><?php echo $count_yesterday_vehicles?></h2>
                                                <span style="color: #fddd;">Yesterday's Vehicles</span>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                        






                            </div>
                        </div>
                    </div>
                        
                        
                        <?php include_once('includes/footer.php'); ?>
                        
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
