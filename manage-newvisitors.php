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
    <title>Manage Visitors</title>

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
                                      <?php $status = isset($_GET['status']) ? $_GET['status'] : 'all'; ?>
                                      <select name="status" id="status" class="form-control" style="max-width:220px;">
                                        <option value="all" <?php echo ($status==='all'? 'selected' : ''); ?>>All</option>
                                        <option value="inside" <?php echo ($status==='inside'? 'selected' : ''); ?>>Currently Inside</option>
                                        <option value="exited_today" <?php echo ($status==='exited_today'? 'selected' : ''); ?>>Exited Today</option>
                                      </select>
                                      <button type="submit" class="btn btn-primary">Apply</button>
                                      <a href="manage-newvisitors.php" class="btn btn-light">Clear</a>
                                    </form>
                                    <?php
                                      // Ensure EnterDate/ExitDate columns exist and allow NULL
                                      $visEnterCol = mysqli_query($con, "SHOW COLUMNS FROM tblvisitor LIKE 'EnterDate'");
                                      if ($visEnterCol && mysqli_num_rows($visEnterCol) == 0) {
                                        @mysqli_query($con, "ALTER TABLE tblvisitor ADD COLUMN EnterDate DATETIME NULL");
                                        $visEnterCol = mysqli_query($con, "SHOW COLUMNS FROM tblvisitor LIKE 'EnterDate'");
                                      }
                                      @mysqli_query($con, "ALTER TABLE tblvisitor MODIFY EnterDate DATETIME NULL");
                                      $visExitCol = mysqli_query($con, "SHOW COLUMNS FROM tblvisitor LIKE 'ExitDate'");
                                      if ($visExitCol && mysqli_num_rows($visExitCol) == 0) {
                                        @mysqli_query($con, "ALTER TABLE tblvisitor ADD COLUMN ExitDate DATETIME NULL");
                                        $visExitCol = mysqli_query($con, "SHOW COLUMNS FROM tblvisitor LIKE 'ExitDate'");
                                      }
                                      @mysqli_query($con, "ALTER TABLE tblvisitor MODIFY ExitDate DATETIME NULL");

                                      $where = '';
                                      if ($status === 'inside') {
                                        $where = "WHERE EnterDate IS NOT NULL AND (ExitDate IS NULL OR ExitDate = '0000-00-00 00:00:00')";
                                      } elseif ($status === 'exited_today') {
                                        $where = "WHERE ExitDate IS NOT NULL AND DATE(ExitDate) = CURDATE()";
                                      }
                                      $ret=mysqli_query($con,"select * from tblvisitor $where order by ID desc");
                                      $cnt=1;
                                    ?>
                                    <table class="table table-borderless table-striped table-earning">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Full Name</th>
                                                <th>Contact Number</th>
                                                <th>Designation / Department</th>
                                                <th>Roll No.</th>
                                                <th>Enter Time</th>
                                                <th>Exit Time</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        while ($row=mysqli_fetch_array($ret)) {
                                          $designation = $row['des'] ?? '';
                                          $department = $row['Deptartment'] ?? '';
                                          $designationDisplay = $designation;
                                          if ($designationDisplay !== '' && $department !== '') {
                                              $designationDisplay .= ' / '.$department;
                                          } elseif ($designationDisplay === '' && $department !== '') {
                                              $designationDisplay = $department;
                                          }
                                          if ($designationDisplay === '') {
                                              $designationDisplay = '-';
                                          }
                                          $enterTime = !empty($row['EnterDate']) ? date('Y-m-d H:i', strtotime($row['EnterDate'])) : '-';
                                          $enterInput = !empty($row['EnterDate']) ? date('Y-m-d\\TH:i', strtotime($row['EnterDate'])) : '';
                                          $exitTime = !empty($row['ExitDate']) ? date('Y-m-d H:i', strtotime($row['ExitDate'])) : '-';
                                          $exitInput = !empty($row['ExitDate']) ? date('Y-m-d\\TH:i', strtotime($row['ExitDate'])) : '';
                                          $exitClass = ($exitTime !== '-') ? 'text-danger font-weight-bold' : 'text-muted';
                                        ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo htmlspecialchars($row['FullName']); ?></td>
                                                <td><?php echo htmlspecialchars($row['MobileNumber']); ?></td>
                                                <td><?php echo htmlspecialchars($designationDisplay); ?></td>
                                                <td><?php echo htmlspecialchars($row['rollno']); ?></td>
                                                <td><?php echo $enterTime; ?></td>
                                                <td><span class="exit-time <?php echo $exitClass; ?>"><?php echo $exitTime; ?></span></td>
                                                <td>
                                                    <a style="border-radius: 10%;padding-left: 10px;padding-right: 10px;padding-top: 2px; background: #1a73e8;color: #fff; margin-right:6px;"
                                                       href="#"
                                                       class="btn-edit-visitor"
                                                       data-id="<?php echo $row['ID']; ?>"
                                                       data-fullname="<?php echo htmlspecialchars($row['FullName'], ENT_QUOTES, 'UTF-8'); ?>"
                                                       data-mobile="<?php echo htmlspecialchars($row['MobileNumber'], ENT_QUOTES, 'UTF-8'); ?>"
                                                       data-des="<?php echo htmlspecialchars($designation, ENT_QUOTES, 'UTF-8'); ?>"
                                                       data-dept="<?php echo htmlspecialchars($department, ENT_QUOTES, 'UTF-8'); ?>"
                                                       data-roll="<?php echo htmlspecialchars($row['rollno'], ENT_QUOTES, 'UTF-8'); ?>"
                                                       data-enter="<?php echo $enterInput; ?>"
                                                       data-exit="<?php echo $exitInput; ?>"
                                                       title="Edit"><i class="fa fa-edit"></i></a>
                                                    <?php $enterBtnHidden = !empty($row['EnterDate']); $exitBtnHidden = !empty($row['ExitDate']); ?>
                                                    <a style="border-radius: 10%;padding-left: 10px;padding-right: 10px;padding-top: 2px; background: #5e35b1;color: #fff; margin-right:6px;<?php echo $enterBtnHidden ? ' display:none;' : ''; ?>"
                                                       class="btn-visitor-enter"
                                                       href="visitor-enter.php?id=<?php echo $row['ID']; ?>&return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>"
                                                       title="Mark Enter"><i class="fa fa-sign-in"></i></a>
                                                    <a style="border-radius: 10%;padding-left: 10px;padding-right: 10px;padding-top: 2px; background: #0f9d58;color: #fff; margin-right:6px;<?php echo $exitBtnHidden ? ' display:none;' : ''; ?>"
                                                       class="btn-visitor-exit"
                                                       href="visitor-exit.php?id=<?php echo $row['ID']; ?>&return=<?php echo urlencode($_SERVER['REQUEST_URI']); ?>"
                                                       title="Mark Exit"><i class="fa fa-sign-out"></i></a>
                                                    <a style="border-radius: 10%;padding-left: 10px;padding-right: 10px;padding-top: 2px; background: #424242;color: #fff; margin-right:6px;"
                                                       href="visitor-detail.php?editid=<?php echo $row['ID']; ?>"
                                                       title="View Full Details"><i class="ri-eye-line"></i></a>
                                                    <a style="border-radius: 10%;padding-left: 10px;padding-right: 10px;padding-top: 2px; background: black;color: yellow;"
                                                       href="delete-detail.php?deleteid=<?php echo $row['ID']; ?>"
                                                       title="Delete Row"><i class="ri-delete-bin-line"></i></a>
                                                </td>
                                            </tr>
                                        <?php
                                          $cnt=$cnt+1;
                                        }
                                        ?>
                                        </tbody>
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
    
    <!-- Edit Visitor Modal -->
    <div class="modal fade" id="editVisitorModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Edit Visitor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form id="editVisitorForm">
            <div class="modal-body">
              <div id="editVisitorAlert" class="alert alert-danger" role="alert" style="display:none;"></div>
              <input type="hidden" name="id" id="vv-id" value="">
              <div class="form-group">
                <label for="vv-fullname">Full Name</label>
                <input type="text" class="form-control" name="fullname" id="vv-fullname" required>
              </div>
              <div class="form-group">
                <label for="vv-mobile">Contact Number</label>
                <input type="text" class="form-control" name="mobile" id="vv-mobile" required>
              </div>
              <div class="form-group">
                <label for="vv-des">Designation</label>
                <input type="text" class="form-control" name="des" id="vv-des">
              </div>
              <div class="form-group">
                <label for="vv-dept">Department</label>
                <input type="text" class="form-control" name="dept" id="vv-dept">
              </div>
              <div class="form-group">
                <label for="vv-roll">Roll No.</label>
                <input type="text" class="form-control" name="rollno" id="vv-roll" required>
              </div>
              <div class="form-group">
                <label for="vv-enter">Enter Time</label>
                <input type="datetime-local" class="form-control" name="enterdate" id="vv-enter">
              </div>
              <div class="form-group">
                <label for="vv-exit">Exit Time</label>
                <input type="datetime-local" class="form-control" name="exitdate" id="vv-exit">
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
        var statusFilter = (function(){
          try { return new URLSearchParams(window.location.search).get('status') || 'all'; }
          catch (e) { return 'all'; }
        })();
        function showError(msg){
          var box = document.getElementById('editVisitorAlert');
          box.style.display = 'block';
          box.textContent = msg || 'Update failed. Please try again.';
        }
        function hideError(){
          var box = document.getElementById('editVisitorAlert');
          box.style.display = 'none';
          box.textContent = '';
        }
        $(document).on('click', '.btn-edit-visitor', function(e){
          e.preventDefault();
          hideError();
          currentRow = $(this).closest('tr');
          var $btn = $(this);
          $('#vv-id').val($btn.data('id'));
          $('#vv-fullname').val($btn.data('fullname'));
          $('#vv-mobile').val($btn.data('mobile'));
          $('#vv-des').val($btn.data('des'));
          $('#vv-dept').val($btn.data('dept'));
          $('#vv-roll').val($btn.data('roll'));
          $('#vv-enter').val($btn.data('enter') || '');
          $('#vv-exit').val($btn.data('exit') || '');
          $('#editVisitorModal').modal('show');
        });

        $('#editVisitorForm').on('submit', function(e){
          e.preventDefault();
          hideError();
          var $form = $(this);
          $.ajax({
            url: 'visitor-update.php',
            method: 'POST',
            data: $form.serialize(),
            dataType: 'json'
          }).done(function(resp){
            if (!resp || !resp.success) { showError(resp && resp.message ? resp.message : null); return; }
            if (statusFilter === 'inside' || statusFilter === 'exited_today') {
              window.location.reload();
              return;
            }
            if (currentRow) {
              var tds = currentRow.find('td');
              tds.eq(1).text(resp.data.fullname);
              tds.eq(2).text(resp.data.mobile);
              tds.eq(3).text(resp.data.des_dept_display);
              tds.eq(4).text(resp.data.rollno);
              tds.eq(5).text(resp.data.enter_display);
              var exitSpan = tds.eq(6).find('.exit-time');
              if (!exitSpan.length) { exitSpan = tds.eq(6); }
              exitSpan.text(resp.data.exit_display);
              exitSpan.removeClass('text-danger text-muted font-weight-bold');
              if (resp.data.exit_has_value) {
                exitSpan.addClass('text-danger font-weight-bold');
              } else {
                exitSpan.addClass('text-muted');
              }
              var editBtn = currentRow.find('.btn-edit-visitor');
              editBtn.attr('data-fullname', resp.data.fullname)
                     .attr('data-mobile', resp.data.mobile)
                     .attr('data-des', resp.data.des)
                     .attr('data-dept', resp.data.dept)
                     .attr('data-roll', resp.data.rollno)
                     .attr('data-enter', resp.data.enter_input)
                     .attr('data-exit', resp.data.exit_input);
              var enterBtn = currentRow.find('.btn-visitor-enter');
              if (resp.data.enter_has_value) {
                enterBtn.hide();
              } else {
                enterBtn.show();
              }
              var exitBtn = currentRow.find('.btn-visitor-exit');
              if (resp.data.exit_has_value) {
                exitBtn.hide();
              } else {
                exitBtn.show();
              }
            }
            $('#editVisitorModal').modal('hide');
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
