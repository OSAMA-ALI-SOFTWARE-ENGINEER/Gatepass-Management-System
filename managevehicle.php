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
    <title>Manage Vehicle</title>

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
      <?php include_once('includes/sidebar.php');?>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->

        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <?php include_once('includes/header.php');?>
            <!-- END HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="table-responsive table--no-card m-b-30">
                                    <?php if(isset($_GET['msg'])) { echo "<div class='alert alert-info' role='alert' style='font-size:16px;'>".htmlspecialchars($_GET['msg'])."</div>"; } ?>
                                    <form method="get" class="form-inline" style="margin-bottom:12px; display:flex; gap:8px; align-items:center;">
                                      <label for="status" style="margin:0 6px 0 0;">Filter:</label>
                                      <?php // preserve other query params (none currently) ?>
                                      <select name="status" id="status" class="form-control" style="max-width:220px;">
                                        <?php $status = isset($_GET['status']) ? $_GET['status'] : 'all'; ?>
                                        <option value="all" <?php echo ($status==='all'? 'selected' : ''); ?>>All</option>
                                        <option value="inside" <?php echo ($status==='inside'? 'selected' : ''); ?>>Currently Inside</option>
                                        <option value="exited_today" <?php echo ($status==='exited_today'? 'selected' : ''); ?>>Exited Today</option>
                                      </select>
                                      <button type="submit" class="btn btn-primary">Apply</button>
                                      <a href="managevehicle.php" class="btn btn-light">Clear</a>
                                    </form>
                                    <?php
                                      // Ensure EnterDate and ExitDate columns exist to avoid runtime errors
                                      $vehEnterCol = mysqli_query($con, "SHOW COLUMNS FROM vehicles LIKE 'EnterDate'");
                                      if ($vehEnterCol && mysqli_num_rows($vehEnterCol) == 0) {
                                        @mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN EnterDate DATETIME NULL");
                                      }
                                      @mysqli_query($con, "ALTER TABLE vehicles MODIFY EnterDate DATETIME NULL");
                                      $vehExitCol = mysqli_query($con, "SHOW COLUMNS FROM vehicles LIKE 'ExitDate'");
                                      if ($vehExitCol && mysqli_num_rows($vehExitCol) == 0) {
                                        @mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN ExitDate DATETIME NULL");
                                      }
                                      @mysqli_query($con, "ALTER TABLE vehicles MODIFY ExitDate DATETIME NULL");
                                      // Build filter
                                      $where = '';
                                      if ($status === 'inside') {
                                        $where = "WHERE EnterDate IS NOT NULL AND (ExitDate IS NULL OR ExitDate = '0000-00-00 00:00:00')";
                                      } elseif ($status === 'exited_today') {
                                        $where = "WHERE ExitDate IS NOT NULL AND DATE(ExitDate) = CURDATE()";
                                      }
                                    ?>
                                    <table class="table table-borderless table-striped table-earning">
                                         <thead>
                                        <tr>
              <th>S.No</th>
              <th>Owner Name</th>
              <th>Registration Number</th>
              <th>Enter Time</th>
              <th>Exit Time</th>
              <th>Action</th>
                </tr>
                                        </thead>
                                       <?php
$ret=mysqli_query($con,"select * from vehicles $where order by id desc");
$cnt=1;
while ($row=mysqli_fetch_array($ret)) {
  $enterTime = !empty($row['EnterDate']) ? date('Y-m-d H:i', strtotime($row['EnterDate'])) : '-';
  $exitTime = !empty($row['ExitDate']) ? date('Y-m-d H:i', strtotime($row['ExitDate'])) : '-';
  $exitClass = ($exitTime !== '-') ? 'text-danger font-weight-bold' : 'text-muted';
?>

                <tr>
                  <td><?php echo $cnt;?></td>
                  <td><?php  echo htmlspecialchars($row['fullname']);?></td>
                  <td><?php  echo htmlspecialchars($row['vnumber']);?></td>
                  <td><?php  echo $enterTime; ?></td>
                  <td><span class="exit-time <?php echo $exitClass; ?>"><?php  echo $exitTime; ?></span></td>
                  <td>
                    <a style="border-radius: 10%;padding-left: 10px;padding-right: 10px;padding-top: 2px; background: #1a73e8;color: #fff; margin-right:6px;"
                       href="#"
                       class="btn-edit-vehicle"
                       data-id="<?php echo $row['id']; ?>"
                       data-fullname="<?php echo htmlspecialchars($row['fullname'], ENT_QUOTES, 'UTF-8'); ?>"
                       data-vnumber="<?php echo htmlspecialchars($row['vnumber'], ENT_QUOTES, 'UTF-8'); ?>"
                       data-enter="<?php echo !empty($row['EnterDate']) ? date('Y-m-d\\TH:i', strtotime($row['EnterDate'])) : ''; ?>"
                       data-exit="<?php echo !empty($row['ExitDate']) ? date('Y-m-d\\TH:i', strtotime($row['ExitDate'])) : ''; ?>"
                       title="Edit"><i class="fa fa-edit"></i></a>
                    <?php if (empty($row['EnterDate'])) { ?>
                      <a style="border-radius: 10%;padding-left: 10px;padding-right: 10px;padding-top: 2px; background: #5e35b1;color: #fff; margin-right:6px;" href="vehicle-enter.php?id=<?php echo $row['id']; ?>&return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" title="Mark Enter"><i class="fa fa-sign-in"></i></a>
                    <?php } ?>
                    <?php if (empty($row['ExitDate'])) { ?>
                      <a style="border-radius: 10%;padding-left: 10px;padding-right: 10px;padding-top: 2px; background: #0f9d58;color: #fff; margin-right:6px;" href="vehicle-exit.php?id=<?php echo $row['id']; ?>&return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>" title="Mark Exit"><i class="fa fa-sign-out"></i></a>
                    <?php } ?>
                    <a style="border-radius: 10%;padding-left: 10px;padding-right: 10px;padding-top: 2px; background: black;color: yellow;" href="delete-vehicle.php?id=<?php echo $row['id'];?>" title="Delete Row"><i class="ri-delete-bin-line"></i></a>
                  </td>
                </tr>
                <?php
$cnt=$cnt+1;
}?>
                                    </table>
                                </div>
                            </div>

                        </div>



<?php include_once('includes/footer.php');?>
          </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Edit Vehicle Modal -->
    <div class="modal fade" id="editVehicleModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Vehicle</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editVehicleForm">
            <div class="modal-body">
              <div id="editVehicleAlert" class="alert alert-danger" role="alert" style="display:none;"></div>
              <input type="hidden" name="id" id="ev-id" value="">
              <div class="form-group">
                <label for="ev-fullname">Owner Name</label>
                <input type="text" class="form-control" name="fullname" id="ev-fullname" required>
              </div>
              <div class="form-group">
                <label for="ev-vnumber">Number Plate</label>
                <input type="text" class="form-control" name="vnumber" id="ev-vnumber" maxlength="10" required>
              </div>
              <div class="form-group">
                <label for="ev-enter">Enter Time</label>
                <input type="datetime-local" class="form-control" name="enterdate" id="ev-enter">
              </div>
              <div class="form-group">
                <label for="ev-exit">Exit Time</label>
                <input type="datetime-local" class="form-control" name="exitdate" id="ev-exit">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
              <button type="submit" class="btn btn-success">Save Changes</button>
            </div>
          </form>
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
    <script>
      (function(){
        var currentRow = null;
        function showError(msg){
          var box = document.getElementById('editVehicleAlert');
          box.style.display = 'block';
          box.textContent = msg || 'Update failed. Please try again.';
        }
        function hideError(){
          var box = document.getElementById('editVehicleAlert');
          box.style.display = 'none';
          box.textContent = '';
        }
        $(document).on('click', '.btn-edit-vehicle', function(e){
          e.preventDefault();
          hideError();
          currentRow = $(this).closest('tr');
          $('#ev-id').val($(this).data('id'));
          $('#ev-fullname').val($(this).data('fullname'));
          $('#ev-vnumber').val($(this).data('vnumber'));
          $('#ev-enter').val($(this).data('enter') || '');
          $('#ev-exit').val($(this).data('exit') || '');
          $('#editVehicleModal').modal('show');
        });

        $('#editVehicleForm').on('submit', function(e){
          e.preventDefault();
          hideError();
          var $form = $(this);
          var payload = $form.serialize();
          $.ajax({
            url: 'vehicle-update.php',
            method: 'POST',
            data: payload,
            dataType: 'json'
          }).done(function(resp){
            if (!resp || !resp.success) { showError(resp && resp.message ? resp.message : null); return; }
            // If filter could move the row out of view, reload the page.
            var qs = new URLSearchParams(window.location.search);
            var status = (qs.get('status') || 'all');
            if (status === 'inside' || status === 'exited_today') {
              window.location.reload();
              return;
            }
            // Update row cells: [1]=name, [2]=plate, [3]=enter, [4]=exit
            if (currentRow) {
              currentRow.find('td').eq(1).text(resp.data.fullname);
              currentRow.find('td').eq(2).text(resp.data.vnumber);
              currentRow.find('td').eq(3).text(resp.data.enter_display);
              var exitCell = currentRow.find('td').eq(4);
              var exitSpan = exitCell.find('.exit-time');
              if (!exitSpan.length) { exitSpan = exitCell; }
              exitSpan.text(resp.data.exit_display);
              exitSpan.removeClass('text-danger text-muted font-weight-bold');
              if (resp.data.exit_has_value) {
                exitSpan.addClass('text-danger font-weight-bold');
              } else {
                exitSpan.addClass('text-muted');
              }
              // Update data-* on the edit button (both cache and attribute)
              var btn = currentRow.find('.btn-edit-vehicle');
              btn.attr('data-fullname', resp.data.fullname).data('fullname', resp.data.fullname);
              btn.attr('data-vnumber', resp.data.vnumber).data('vnumber', resp.data.vnumber);
              btn.attr('data-enter', resp.data.enter_input).data('enter', resp.data.enter_input);
              btn.attr('data-exit', resp.data.exit_input).data('exit', resp.data.exit_input);
            }
            $('#editVehicleModal').modal('hide');
          }).fail(function(xhr, status, errorThrown){
            try {
              var json = JSON.parse(xhr.responseText);
              showError(json && (json.message || json.sql) ? (json.message || json.sql) : null);
            } catch (e) {
              var snippet = (xhr && xhr.responseText) ? String(xhr.responseText).replace(/<[^>]+>/g,' ').trim().slice(0,180) : '';
              showError(snippet || (status + ': ' + (errorThrown||'Request failed')));
            }
          });
        });
      })();
    </script>

</body>

</html>
<?php }  ?>
