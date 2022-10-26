<?php
$con= mysqli_connect("localhost","root","123456789","my_db_web_project") or die("Error: " . mysqli_error($con));
mysqli_query($con, "SET NAMES 'utf8' "); 
?>