<?php
$con=mysqli_connect("localhost", "root", "", "cvmsdb");
if(mysqli_connect_errno()){
echo "Connection Fail".mysqli_connect_error();
}

// Intentionally omit closing PHP tag to avoid accidental output that
// can break header redirects on pages that include this file.
