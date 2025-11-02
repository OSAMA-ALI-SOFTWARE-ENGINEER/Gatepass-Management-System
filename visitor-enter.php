<?php
session_start();
error_reporting(0);
include('includes/dbconnection.php');
if (function_exists('mysqli_report')) {
  mysqli_report(MYSQLI_REPORT_OFF);
}
if (strlen($_SESSION['cvmsaid']==0)) {
  header('location:logout.php');
  exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
  header('Location: manage-newvisitors.php');
  exit();
}

@mysqli_query($con, "ALTER TABLE tblvisitor ADD COLUMN EnterDate DATETIME NULL");
@mysqli_query($con, "ALTER TABLE tblvisitor MODIFY EnterDate DATETIME NULL");

$ok = mysqli_query($con, "UPDATE tblvisitor SET EnterDate = NOW() WHERE ID=$id");
$msg = $ok ? 'Enter time recorded.' : 'Unable to record enter time.';

$return = isset($_GET['return']) ? $_GET['return'] : '';
$target = 'manage-newvisitors.php';
if ($return !== '' && strpos($return, '://') === false) {
  $parsed = parse_url($return);
  if (!isset($parsed['path']) || basename($parsed['path']) === 'manage-newvisitors.php') {
    $target = $return;
  }
}
$sep = (strpos($target, '?') !== false) ? '&' : '?';
$redirectUrl = $target.$sep.'msg='.urlencode($msg);
if (!headers_sent()) {
  header('Location: '.$redirectUrl);
  exit();
}
echo '<!DOCTYPE html><html><head><meta http-equiv="refresh" content="0;url='.htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8').'" /></head><body>';
echo '<p>Redirecting… <a href="'.htmlspecialchars($redirectUrl, ENT_QUOTES, 'UTF-8').'">click here</a>.</p>';
echo '<script>window.location.href='.json_encode($redirectUrl).';</script>';
echo '</body></html>';
exit();
?>

