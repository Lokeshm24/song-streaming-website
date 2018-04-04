<?php
session_start();
?>
<html>
<head>
<title>
</title>
<link rel="stylesheet" type="text/css" href="songs.css">
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="nav-bar">
<div id="inline">
<img src="logo.png" height="70px" width="150px"></img>
</div>
<div id="inline">
			<a href="homepage.php" class="pages active">Songs</a>
			<a href="karaoke.php" class="pages">Karaoke</a>
			<a href="about.html" class="pages">About</a>
			<a href="help.html" class="pages">Help</a>
</div>
<div id="inline" class="play">
<?php if(isset($_GET['name'])){ 
echo '<audio controls id="playSong" loop>
<source src="'.$_GET['name'].'" type="audio/mpeg" autoplay="autoplay"></source>
</audio>';
}
?>
</div>
<div id="inline" class="login-button">
<?php
    if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
		echo '<button type="button" class="btn btn-info btn-lg login" data-toggle="modal" data-target="#myModal">
Login
</button>
<button type="button" class="btn btn-info btn-lg login" data-toggle="modal" data-target="#myModal1">
Signup
</button>';

    }
	else {
		echo '<p style="display:inline; font-size: 20px">Hi, <strong>'.$_SESSION['username'].'<strong><p><a href="logout.php" class="btn btn-danger">Logout</a></p></p>';
		if(isset($_GET['name'])){
			echo '<a href="download.php?file='.urlencode($_GET['name']).'"><button class="fa fa-arrow-down downloadBtn"></button></a>';
		}
	}

    ?>
	
</div>
</div>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    <?php

    // Include config file

    require_once 'config.php';

     

    // Define variables and initialize with empty values

    $username = $password = "";

    $username_err = $password_err = "";

     
	
    // Processing form data when form is submitted

    if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {

     
        // Check if username is empty

        if(empty(trim($_POST["username"]))){

            $username_err = 'Please enter username.';

        } else{

            $username = trim($_POST["username"]);

        }

        

        // Check if password is empty

        if(empty(trim($_POST['password']))){

            $password_err = 'Please enter your password.';

        } else{

            $password = trim($_POST['password']);

        }

        

        // Validate credentials

        if(empty($username_err) && empty($password_err)){

            // Prepare a select statement

            $sql = "SELECT username, password FROM users WHERE username = ?";
			
            if($stmt = mysqli_prepare($link, $sql)){

                // Bind variables to the prepared statement as parameters

                mysqli_stmt_bind_param($stmt, "s", $param_username);

                

                // Set parameters

                $param_username = $username;

                

                // Attempt to execute the prepared statement

                if(mysqli_stmt_execute($stmt)){

                    // Store result

                    mysqli_stmt_store_result($stmt);

                    

                    // Check if username exists, if yes then verify password

                    if(mysqli_stmt_num_rows($stmt) == 1){                    

                        // Bind result variables

                        mysqli_stmt_bind_result($stmt, $username, $hashed_password);

                        if(mysqli_stmt_fetch($stmt)){

                            if(password_verify($password, $hashed_password)){

                                /* Password is correct, so start a new session and

                                save the username to the session */

                                

                                $_SESSION['username'] = $username;      

                                //header("location: homepage.php");

                            } else{

                                // Display an error message if password is not valid

                                $password_err = 'The password you entered was not valid.';

                            }

                        }

                    } else{

                        // Display an error message if username doesn't exist

                        $username_err = 'No account found with that username.';

                    }

                } else{

                    echo "Oops! Something went wrong. Please try again later.";

                }

            }

            

            // Close statement

            mysqli_stmt_close($stmt);
        }

        

        // Close connection

        mysqli_close($link);

    }

    ?>

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Login</h4>
		  </div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">		  
   
        <div class="modal-body" style="padding: 0px 15px 16px 15px">		  
		  <div class="container" style="padding: 16px 15px 32px 15px">
		  <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
    <label style="margin-bottom: 0px"><h4>Username</h4></label><br>
    <input type="text" placeholder="Enter Username" class="inputBox" name="username" value="<?php echo $username; ?>" required>
	<span class="help-block"><?php echo $username_err; ?></span>
	</div>
<br>
<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
    <label><h4>Password</h4></label><br>
    <input type="password" placeholder="Enter Password" class="inputBox" name="password" required>
	<span class="help-block"><?php echo $password_err; ?></span>
        </div>
</div>
  <div class="">
    <!--<button type="button" class="cancelbtn">Cancel</button>-->
         <button type="submit" class="btn btn-default" id="login-modal" name="login"><b>Login</b></button>
		 <button type="button"class="btn btn-default" data-dismiss="modal" id="login-modal1">Cancel</button>
		 </div>
        </div>
		</form>
      </div>
      
    </div>
  </div>
  <div class="modal fade" id="myModal1" role="dialog">
    <div class="modal-dialog">
     <?php

    // Include config file

    require_once 'config.php';

     

    // Define variables and initialize with empty values

    $username = $password = $confirm_password = "";

    $username_err = $password_err = $confirm_password_err = "";

     

    // Processing form data when form is submitted

    if($_SERVER["REQUEST_METHOD"] == "POST"  && isset($_POST["signup"])){

     
        // Validate username

        if(empty(trim($_POST["username"]))){

            $username_err = "Please enter a username.";

        } else{

            // Prepare a select statement

            $sql = "SELECT id FROM users WHERE username = ?";

            

            if($stmt = mysqli_prepare($link, $sql)){

                // Bind variables to the prepared statement as parameters

                mysqli_stmt_bind_param($stmt, "s", $param_username);

                

                // Set parameters

                $param_username = trim($_POST["username"]);

                

                // Attempt to execute the prepared statement

                if(mysqli_stmt_execute($stmt)){

                    /* store result */

                    mysqli_stmt_store_result($stmt);

                    

                    if(mysqli_stmt_num_rows($stmt) == 1){

                        $username_err = "This username is already taken.";

                    } else{

                        $username = trim($_POST["username"]);

                    }

                } else{

                    echo "Oops! Something went wrong. Please try again later.";

                }

            }

             

            // Close statement

            mysqli_stmt_close($stmt);

        }

        

        // Validate password

        if(empty(trim($_POST['password']))){

            $password_err = "Please enter a password.";     

        } elseif(strlen(trim($_POST['password'])) < 6){

            $password_err = "Password must have atleast 6 characters.";

        } else{

            $password = trim($_POST['password']);

        }

        

        // Validate confirm password

        if(empty(trim($_POST["confirm_password"]))){

            $confirm_password_err = 'Please confirm password.';     

        } else{

            $confirm_password = trim($_POST['confirm_password']);

            if($password != $confirm_password){

                $confirm_password_err = 'Password did not match.';

            }

        }

        

        // Check input errors before inserting in database

		echo "tinde lelo 1" .$username_err.$password_err.$confirm_password_err;
        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){


		echo "tinde lelo";            

            // Prepare an insert statement

            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

             

            if($stmt = mysqli_prepare($link, $sql)){

                // Bind variables to the prepared statement as parameters

                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

                

                // Set parameters

                $param_username = $username;

                $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

                

                // Attempt to execute the prepared statement

                if(mysqli_stmt_execute($stmt)){

                    // Redirect to login page

                    header("location: homepage.php");

                } else{

                    echo "Something went wrong. Please try again later.";

                }

            }

             

            // Close statement

            mysqli_stmt_close($stmt);

        }

        

        // Close connection

        mysqli_close($link);

    }

    ?>
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Signup</h4>
		  </div>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"  method="post">		  
   
        <div class="modal-body" style="padding: 0px 15px 16px 15px">		  
		  <div class="container" style="padding: 16px 15px 32px 15px">
		  <label><h4>Username</h4></label><br>
    <input type="text" placeholder="Enter Username" name="username" class="inputBox" required>
<br>
    <label><h4>Password</h4></label><br>
    <input type="password" placeholder="Enter Password" name="password" class="inputBox" required><br>
	
	<label><h4>Repeat Password</h4></label><br>
      <input type="password" placeholder="Repeat Password" name="confirm_password" class="inputBox" required>
        </div>

  <div>
    <!--<button type="button" class="cancelbtn">Cancel</button>-->
		 <button type="submit" class="btn btn-default" id="login-modal" name="signup">Sign Up</button>
         <button type="button" class="btn btn-default" data-dismiss="modal" id="login-modal1">Cancel</button>
		 </div>
        </div>
		</form>
      </div>
      
    </div>
  </div>
<div class="category">
<ul>
<div id="inline" class="active" onclick="change1()">
<li>Songs</li>
</div>
<a href="hompage.php"><div id="inline" onclick="change()"><li>Playlist</li></div></a>
</ul>
</div>
<div>
<div id="songs">
<ul>
<?php 
displayAudios();
if (isset($_GET['name'])){
	echo '<script>
    var song = document.getElementById("playSong");
	var isPlaying = false;
	myFunction();
function myFunction() {
	playPause();
	if(isPlaying)
		song.style.visibility = "visible";
}
function playPause(){
	isPlaying = !isPlaying;
	if(isPlaying){
		song.play();
	}
	else{
		song.pause();
	}
}
</script>';
}

function displayAudios(){
	$conn=mysqli_connect('localhost','root','','jhoomjingle');
	if(!$conn){
		die('server not connected.');
	}
	
	$query="select * from jhoomjingle.audios";
	
	$r=mysqli_query($conn, $query);
	
	while($row = mysqli_fetch_array($r)){
		$song = explode('/', $row['filename']);
		$song = explode('.mp3',$song[1]);
		$song = str_replace("_", " ", $song);
		$song = array_map('ucfirst', $song);
		echo '<li><a href="homepage.php?name='.$row['filename'].'" style = "cursor: pointer"><button id="playButton" class="fa fa-play playButton"></button>&nbsp;&nbsp;&nbsp;'.$song[0].'</a></li>';
	}
	
	mysqli_close($conn);
}

?> 
</ul>
</div>
<div id="playlist" class="notActive">
Hello this is playlist
</div>
</div>
<script>
function change(){
	var playlist = document.getElementById("playlist");
	var songsTab = document.getElementById("songs");
	if(playlist.classList.contains(".notActive)")){
		songsTab.classList.add(".notActive");
		playlist.classList.remove(".notActive");
	}
}
</script>
</body>
</html>