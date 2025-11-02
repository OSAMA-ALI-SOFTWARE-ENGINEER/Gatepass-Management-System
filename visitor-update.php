<?php
session_start();
error_reporting(0);
header('Content-Type: application/json');
include('includes/dbconnection.php');
if (function_exists('mysqli_report')) {
  mysqli_report(MYSQLI_REPORT_OFF);
}

if (strlen($_SESSION['cvmsaid']==0)) {
  echo json_encode(['success' => false, 'message' => 'Unauthorized']);
  exit();
}

// Ensure schema
@mysqli_query($con, "ALTER TABLE tblvisitor ADD COLUMN EnterDate DATETIME NULL");
@mysqli_query($con, "ALTER TABLE tblvisitor ADD COLUMN ExitDate DATETIME NULL");
@mysqli_query($con, "ALTER TABLE tblvisitor MODIFY EnterDate DATETIME NULL");
@mysqli_query($con, "ALTER TABLE tblvisitor MODIFY ExitDate DATETIME NULL");

$id        = isset($_POST['id']) ? intval($_POST['id']) : 0;
$fullname  = isset($_POST['fullname']) ? trim($_POST['fullname']) : '';
$mobile    = isset($_POST['mobile']) ? trim($_POST['mobile']) : '';
$des       = isset($_POST['des']) ? trim($_POST['des']) : '';
$dept      = isset($_POST['dept']) ? trim($_POST['dept']) : '';
$rollno    = isset($_POST['rollno']) ? trim($_POST['rollno']) : '';
$enterRaw  = isset($_POST['enterdate']) ? trim($_POST['enterdate']) : '';
$exitRaw   = isset($_POST['exitdate']) ? trim($_POST['exitdate']) : '';

if ($id <= 0 || $fullname === '' || $rollno === '') {
  echo json_encode(['success'=>false,'message'=>'Invalid input']);
  exit();
}

$normDate = function($raw) {
  if ($raw === null) return null;
  $raw = trim($raw);
  if ($raw === '') return null;
  $plain = strtolower(str_replace([' ', '\\'], '', $raw));
  if (strpos($plain, 'mm/dd/yyyy') !== false || strpos($plain, '--:--') !== false) {
    return null;
  }
  $raw = str_replace('T', ' ', $raw);
  $ts = strtotime($raw);
  if ($ts === false) {
    $dt = DateTime::createFromFormat('m/d/Y h:i A', $raw);
    if ($dt !== false) {
      return $dt->format('Y-m-d H:i:s');
    }
    return null;
  }
  return date('Y-m-d H:i:s', $ts);
};

$enterDate = $normDate($enterRaw);
$exitDate  = $normDate($exitRaw);

$fullnameEsc = mysqli_real_escape_string($con, $fullname);
$mobileEsc   = mysqli_real_escape_string($con, $mobile);
$desEsc      = mysqli_real_escape_string($con, $des);
$deptEsc     = mysqli_real_escape_string($con, $dept);
$rollnoEsc   = mysqli_real_escape_string($con, $rollno);

$setEnter = is_null($enterDate) ? "EnterDate = NULL" : "EnterDate = '".mysqli_real_escape_string($con, $enterDate)."'";
$setExit  = is_null($exitDate)  ? "ExitDate = NULL"  : "ExitDate = '".mysqli_real_escape_string($con, $exitDate)."'";

$sql = "UPDATE tblvisitor SET FullName='$fullnameEsc', MobileNumber='$mobileEsc', des='$desEsc', Deptartment='$deptEsc', rollno='$rollnoEsc', $setEnter, $setExit WHERE ID=$id";
$ok = mysqli_query($con, $sql);
if (!$ok) {
  echo json_encode(['success'=>false,'message'=>'Database error: '.mysqli_error($con),'sql'=>$sql]);
  exit();
}

$enterInput   = $enterDate ? date('Y-m-d\TH:i', strtotime($enterDate)) : '';
$exitInput    = $exitDate  ? date('Y-m-d\TH:i', strtotime($exitDate))  : '';
$enterDisplay = $enterDate ? date('Y-m-d H:i', strtotime($enterDate))   : '-';
$exitDisplay  = $exitDate  ? date('Y-m-d H:i', strtotime($exitDate))    : '-';

$desDeptDisplay = $des;
if ($desDeptDisplay !== '' && $dept !== '') {
  $desDeptDisplay .= ' / '.$dept;
} elseif ($desDeptDisplay === '' && $dept !== '') {
  $desDeptDisplay = $dept;
}
if ($desDeptDisplay === '') {
  $desDeptDisplay = '-';
}

$response = [
  'success' => true,
  'data' => [
    'id' => $id,
    'fullname' => $fullname,
    'mobile' => $mobile,
    'des' => $des,
    'dept' => $dept,
    'rollno' => $rollno,
    'des_dept_display' => $desDeptDisplay,
    'enter_input' => $enterInput,
    'exit_input' => $exitInput,
    'enter_display' => $enterDisplay,
    'exit_display' => $exitDisplay,
    'enter_has_value' => (bool)$enterDate,
    'exit_has_value' => (bool)$exitDate,
  ],
];

$json = json_encode($response);
if ($json === false) {
  $msg = 'JSON encode error: '.json_last_error_msg();
  echo '{"success":false,"message":"'.addslashes($msg).'"}';
  exit();
}

echo $json;
exit();
?>

