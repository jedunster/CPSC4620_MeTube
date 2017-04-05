<html>
<head>
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
</head>
<body>

<?php
ini_set('session.save_path', getcwd(). '/tmp');
session_start();

include_once "function.php";

if(isset($_POST['submit'])) {
	if( $_POST['passowrd1'] != $_POST['passowrd2']) {
		$register_error = "Passwords don't match. Try again?";
	}
	else {
		$check = add_account_to_db($_POST['username'], $_POST['passowrd1']);	
		if($check == 1){
			//echo "Rigister succeeds";
			$_SESSION['username']=$_POST['username'];
			header('Location: browse.php');
		}
		else if($check == 2){
			$register_error = "Username already exists. Please user a different username.";
		}
	}
}

?>
<form action="register.php" method="post">
	Username: <input type="text" name="username"> <br>
	Create Password: <input  type="password" name="passowrd1"> <br>
	Repeat password: <input type="password" name="passowrd2"> <br>
	<input name="submit" type="submit" value="Submit">
</form>

<?php
  if(isset($register_error))
   {  echo "<div id='passwd_result'> register_error: ".$register_error."</div>";}
?>

</body>
</html>
