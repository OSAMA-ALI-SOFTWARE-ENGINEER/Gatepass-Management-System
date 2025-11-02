<?php
session_id("cli");
session_start();
$_SESSION['cvmsaid']=1;
$_POST = [
  'id' => 8,
  'fullname' => 'Noel Dudle',
  'vnumber' => '583',
  'enterdate' => '11/03/2025 01:46 AM',
  'exitdate' => '11/03/2025 06:58 AM'
];
include 'vehicle-update.php';
