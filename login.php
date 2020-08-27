<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	

	if(isset($_FILES['picToUpload']))
		{
			$filetype = $_FILES['picToUpload']['type'];
			$filesize = $_FILES['picToUpload']['size'];
		
		if($filesize < (1024*1024) && ($filetype=="image/jpg"||$filetype=="image/jpeg"))
		{
			$dir = "gallery/";
			$uploadfile = $dir . basename($_FILES['picToUpload']['name']);
			move_uploaded_file($_FILES['picToUpload']['tmp_name'], $uploadfile);

			$query = "SELECT user_id FROM tbusers WHERE email = '$email' AND password = '$pass'";
			$res = $mysqli->query($query);
			$row = mysqli_fetch_array($res);

			$querys = "INSERT INTO tbgallery (user_id, filename) VALUES ('".$row['user_id']."', '".$uploadfile."')";
			$res = $mysqli->query($querys);
		}}
		
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Stephen Du Plessis">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
		
		

			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form action='login.php' method='POST' enctype='multipart/form-data' >
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input type='hidden' class='form-control' name='loginEmail' value='".$row['email']."'></input>
									<input type='hidden' class='form-control' name='loginPass' value='".$row['password']."'></input>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
							  </form>
							  <h1>Image Gallery</h1>
							  
							  ";
							  $query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
								$res = $mysqli->query($query);
								$row = mysqli_fetch_array($res);
								$query = "SELECT * FROM tbgallery WHERE user_id = '".$row['user_id']."'";
								$res = $mysqli->query($query);
								
								if ($res->num_rows > 0){
								echo '<div class="row imageGallery">';
								while($pics = $res->fetch_assoc()) {
									echo '<div class="col-3" style="background-image: url('.$pics["filename"].'")>
										</div>';
								};
								echo '</div>';
							} 
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>