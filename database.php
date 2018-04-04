<?php
$connect=mysqli_connect('localhost','root','','jhoomjingle');
 
if(mysqli_connect_errno($connect))
{
		echo 'Failed to connect';
}
?>