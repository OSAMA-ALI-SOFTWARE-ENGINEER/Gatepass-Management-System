<?php
// JSON endpoint to update a vehicle
session_start();
error_reporting(0);
header('Content-Type: application/json');
include('includes/dbconnection.php');

if (function_exists('mysqli_report')) {
  mysqli_report(MYSQLI_REPORT_OFF);
}

$logFile = __DIR__.'/storage/logs/vehicle-update.log';
$log = function($message) use ($logFile) {
  file_put_contents($logFile, date('c')." $message\n", FILE_APPEND);
};
if (strlen($_SESSION['cvmsaid']==0)) {
  $log('AUTH FAIL: session missing cvmsaid');
  echo json_encode(['success'=>false,'message'=>'Unauthorized']);
  exit();
}

// Ensure columns exist and allow NULL
@mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN EnterDate DATETIME NULL");
@mysqli_query($con, "ALTER TABLE vehicles ADD COLUMN ExitDate DATETIME NULL");
@mysqli_query($con, "ALTER TABLE vehicles MODIFY EnterDate DATETIME NULL");
@mysqli_query($con, "ALTER TABLE vehicles MODIFY ExitDate DATETIME NULL");

$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$fullname = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
$vnumber  = isset($_POST['vnumber']) ? trim($_POST['vnumber']) : '';
$enterRaw = isset($_POST['enterdate']) ? trim($_POST['enterdate']) : '';
$exitRaw  = isset($_POST['exitdate']) ? trim($_POST['exitdate']) : '';

if ($id <= 0 || $fullname === '' || $vnumber === '') {
  $log('VALIDATION FAIL: invalid payload');
  echo json_encode(['success'=>false,'message'=>'Invalid input']);
  exit();
}

function norm_dt($raw){
  if ($raw === '' || $raw === null) return null;
  $raw = trim($raw);
  // Treat placeholder-like values as empty
  $pl = strtolower(str_replace([' ', '\\'], '', $raw));
  if (strpos($pl, 'mm/dd/yyyy') !== false || strpos($pl, '--:--') !== false) {
    return null;
  }
  // common cleanup from datetime-local
  $raw = str_replace('T', ' ', $raw);
  $ts = strtotime($raw);
  if ($ts === false) {
    // try common US format e.g. 11/03/2025 01:13 AM
    $dt = DateTime::createFromFormat('m/d/Y h:i A', $raw);
    if ($dt !== false) return $dt->format('Y-m-d H:i:s');
  }
  return $ts ? date('Y-m-d H:i:s', $ts) : null;
}

$enterDate = norm_dt($enterRaw);
$exitDate  = norm_dt($exitRaw);

$fullnameEsc = mysqli_real_escape_string($con, $fullname);
$vnumberEsc  = mysqli_real_escape_string($con, $vnumber);
$setEnter = is_null($enterDate) ? "EnterDate = NULL" : "EnterDate = '".mysqli_real_escape_string($con, $enterDate)."'";
$setExit  = is_null($exitDate)  ? "ExitDate = NULL"  : "ExitDate = '".mysqli_real_escape_string($con, $exitDate)."'";

$sql = "UPDATE vehicles SET fullname='$fullnameEsc', vnumber='$vnumberEsc', $setEnter, $setExit WHERE id=$id";
$ok = mysqli_query($con, $sql);
if (!$ok) {
  $log('DB ERROR: '.mysqli_error($con).' | SQL: '.$sql);
  echo json_encode(['success'=>false,'message'=>'Database error: '.mysqli_error($con),'sql'=>$sql]);
  exit();
}

// Prepare display/input formats for the table and modal
$enter_input   = $enterDate ? date('Y-m-d\TH:i', strtotime($enterDate)) : '';
$exit_input    = $exitDate  ? date('Y-m-d\TH:i', strtotime($exitDate))  : '';
$enter_display = $enterDate ? date('Y-m-d H:i',  strtotime($enterDate))  : '-';
$exit_display  = $exitDate  ? date('Y-m-d H:i',  strtotime($exitDate))   : '-';

$response = [
  'success' => true,
  'data' => [
    'id' => $id,
    'fullname' => $fullname,
    'vnumber' => $vnumber,
    'enter_input' => $enter_input,
    'exit_input' => $exit_input,
    'enter_display' => $enter_display,
    'exit_display' => $exit_display,
    'exit_has_value' => (bool)$exitDate,
  ],
];
$json = json_encode($response);
if ($json === false) {
  $msg = 'JSON encode error: '.json_last_error_msg();
  $log('JSON ERROR: '.$msg);
  echo '{"success":false,"message":"'.addslashes($msg).'"}';
  exit();
}
echo $json;
exit();
?>
