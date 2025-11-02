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
  header('Location: managevehicle.php');
  exit();
}

// Ensure column exists and allows NULL
@mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN EnterDate DATETIME NULL");
@mysqli_query($con, "ALTER TABLE vehicles MODIFY EnterDate DATETIME NULL");

$ok = mysqli_query($con, "UPDATE vehicles SET EnterDate = NOW() WHERE id=$id");
$msg = $ok ? 'Enter time recorded.' : 'Unable to record enter time.';

// Redirect back to current Manage Vehicle view (preserve filters)
$return = isset($_GET['return']) ? $_GET['return'] : '';
$target = 'managevehicle.php';
if ($return !== '' && strpos($return, '://') === false) {
  $parsed = parse_url($return);
  if (!isset($parsed['path']) || basename($parsed['path']) === 'managevehicle.php') {
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
