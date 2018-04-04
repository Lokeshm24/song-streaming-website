<?php include 'database.php';?>

<?php

// create a variable
$first_name=mysqli_real_escape_string($connect ,$_POST['first_name']);
$last_name=mysqli_real_escape_string($connect ,$_POST['last_name']);
$department=mysqli_real_escape_string($connect ,$_POST['department']);
$email=mysqli_real_escape_string($connect ,$_POST['email']);

//Execute the query

//mysqli_query($connect,"INSERT INTO employees1(first_name,last_name,department,email) 
//		VALUES('$first_name','$last_name','$department','$email')");

echo $first_name;
?>