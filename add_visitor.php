<form action="save_visitor.php" method="POST" enctype="multipart/form-data">
  <label>Full Name:</label>
  <input type="text" name="FullName" required><br>

  <label>Roll No:</label>
  <input type="text" name="rollno" required><br>

  <label>Department:</label>
  <input type="text" name="Deptartment" required><br>

  <label>Photo:</label>
  <input type="file" name="photo" accept="image/*" required><br>

  <button type="submit">Save</button>
</form>

