<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php ini_set('post_max_size', '40M');
ini_set('upload_max_filesize', '40M'); 
?>
<form action="upload.php" method="POST" enctype="multipart/form-data">
<input type="file" name="audioFile"/>
<input type="submit" value="Upload Audio" name="save_audio"/>
</form>
</body>
</html>