<?php
include('dbconnection.php');

$id = $_GET['id']; // value from QR code

// ✅ 1. Insert scan record into scan_log table
mysqli_query($con, "INSERT INTO scan_log (rollno) VALUES ('$id')");

// ✅ 2. Fetch visitor data from tblvisitor table
$query = mysqli_query($con, "SELECT * FROM tblvisitor WHERE rollno='$id'");
$data = mysqli_fetch_assoc($query);

// ✅ 3. Return visitor data as JSON
echo json_encode($data);
mysqli_query($con, "INSERT INTO scan_log (rollno) VALUES ('$id')");
?>

<?php
// fetch_data.php

session_start();
error_reporting(0);
// Make sure includes/dbconnection.php is in the correct relative path
include('includes/dbconnection.php'); 

// Set the response type to JSON
header('Content-Type: application/json');

// Check if the ID parameter exists in the URL (from the QR code)
if (isset($_GET['id']) && $_GET['id'] != '') {
    $visitor_id = $_GET['id'];
    
    // Sanitize the input for security
    $visitor_id = mysqli_real_escape_string($con, $visitor_id);

    // SQL Query to fetch the required visitor details
    // NOTE: 'ID' is assumed to be the unique value stored in the QR code
    $query = mysqli_query($con, 
        "SELECT FullName, rollno, Deptartment, photo 
         FROM tblvisitor 
         WHERE ID = '$visitor_id'"
    );
    
    // Check if a row was returned
    if ($row = mysqli_fetch_assoc($query)) {
        // Data found, encode as JSON and output
        echo json_encode($row);
    } else {
        // No record found, output null
        echo json_encode(null);
    }
} else {
    // ID not provided
    echo json_encode(null);
}

// Close the database connection (optional, but good practice)
if (isset($con)) {
    mysqli_close($con);
}
?>