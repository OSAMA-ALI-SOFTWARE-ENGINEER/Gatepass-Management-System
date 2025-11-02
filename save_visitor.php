<?php
include('dbconnection.php');

// Get form data
$name = $_POST['FullName'];
$rollno = $_POST['rollno'];
$dept = $_POST['Deptartment'];
$des = $_POST['des'];

// __Step 1: Create folder if not exists__
if (!file_exists('uploads')) {
    mkdir('uploads', 0777, true);
}

// __Step 2: Upload image__
$photo = "uploads/abid.jfif" . basename($_FILES["photo"]["name"]);
move_uploaded_file($_FILES["photo"]["tmp_name"], $photo);

// __Step 3: Save data + photo path in database__
$sql = "INSERT INTO tblvisitor (FullName, rollno, Deptartment, des, photo)
  
        VALUES ('$name', '$rollno', '$dept', '$des', '$photo')";

if (mysqli_query($con, $sql)) {
    echo "✅ Visitor added successfully!";
} else {
    echo "❌ Error: " . mysqli_error($con);
}
?>





<?php
include('dbconnection.php');


if(isset($_POST['submit'])) {

    // --- 1. FIRST PERSON DATA RETRIEVAL (From Form Submission) ---

    $fname = mysqli_real_escape_string($con, $_POST['fullname']);

    $rollno = mysqli_real_escape_string($con, $_POST['rollno']);

    $dept = mysqli_real_escape_string($con, $_POST['deptartment']);

    $mobile = mysqli_real_escape_string($con, $_POST['mobilenumber']);

    $email = mysqli_real_escape_string($con, $_POST['email']);

   

    // File and Path Setup

    $photo_name = $_FILES['visitorphoto']['name'];

    $photo_tmp = $_FILES['visitorphoto']['tmp_name'];

    $photo_path = "uploads/" . $photo_name; // Correct Destination path

   

    // --- 2. FILE UPLOAD AND FIRST INSERTION ---

    if (move_uploaded_file($photo_tmp, $photo_path)) {

       

        // --- 2a. FIRST INSERTION (Data from the form) ---

        $query1 = mysqli_query($con,

            "INSERT INTO tblvisitor (FullName, rollno, Deptartment, MobileNumber, Email, photo)

             VALUES ('$fname', '$rollno', '$dept', '$mobile', '$email', '$photo_path')");

       

        if ($query1) {

            // Get the ID for the first visitor (Needed for QR Code!)

            $visitor_id_1 = mysqli_insert_id($con);

           

            echo "<script>alert('First Visitor (ID: $visitor_id_1) Added Successfully! QR Code can now be generated.');</script>";



            // ----------------------------------------------------------------------

            // --- 3. SECOND PERSON DATA (Hardcoded/Themself Added Data) ---

            // ----------------------------------------------------------------------

           

            // Naye variables define karein (Sanitized already)

            $fname2 = "Muhammad Tariq";

            $rollno2 = "S22-1161";

            $dept2 = "Software Engineering";

            $mobile2 = "03459876543";

            $email2 = "taree4215402@gmail.com";

            $photo_path2 = "uploads/tariq.png"; // Assuming a default photo file is ready

           

            // --- 4. SECOND INSERTION ---

            $query2 = mysqli_query($con,

                "INSERT INTO tblvisitor (FullName, rollno, Deptartment, MobileNumber, Email, photo)

                 VALUES ('$fname2', '$rollno2', '$dept2', '$mobile2', '$email2', '$photo_path2')");

           

            if ($query2) {

                // Get the ID for the second visitor

                $visitor_id_2 = mysqli_insert_id($con);

                echo "<script>alert('Second Visitor (ID: $visitor_id_2) Added Successfully!');</script>";

            } else {

                echo "<script>alert('Error: Second Visitor Data could not be added. Debug: " . mysqli_error($con) . "');</script>";

            }



        } else {

            // Error for first insertion

            echo "<script>alert('Error: First Visitor Data could not be added to the database. Debug: " . mysqli_error($con) . "');</script>";

        }

    } else {

        // Error for file upload

        echo "<script>alert('Error: Photo upload failed for the first visitor.');</script>";

    }

}
if (mysqli_query($con, $sql)) {
    echo "✅ Visitor added successfully!";
} else {
    echo "❌ Error: " . mysqli_error($con);
}
// --- Note: The redundant closing 'if' blocks at the end of your original code are removed here.

?> 