<!-- PHP sign in code from https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php -->
<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Startup files
require_once "config.php";
require_once "checkID.php";

$carName = $numberPlate = $message = "";
$fullName = $email = $phoneNumber = "";

$query = "SELECT carName, numberPlate FROM usercar WHERE carID = '$userID'";
$query2 = "SELECT fullName, email, phoneNumber FROM userdetails WHERE userID = '$userID'";
$vehicleDetails = $link->query($query);
$userDetails = $link->query($query2);
if ($userDetails != null){
	$userDetails = mysqli_fetch_assoc($userDetails);
}
if ($vehicleDetails != null){
	$vehicleDetails = mysqli_fetch_assoc($vehicleDetails);
}

if(isset($_POST['contactBtn'])){ //check if form was submitted
	$fullName = $_POST["fullName"];
	$email = $_POST["email"];
	$phoneNumber = $_POST["phoneNumber"];
	$fullName = ($fullName ?: $userDetails["fullName"]); // if fullName is empty then equal previous entry
	$email = ($email ?: $userDetails["email"]);
	if(preg_match("/^[0-9]{5} [0-9]{6}$/", $phoneNumber)) {
		echo "Gucci";
	}else {
		$phoneNumber = $userDetails["phoneNumber"];
	}
	if ($userDetails["fullName"] == null && $userDetails["email"] == null && $userDetails["phoneNumber"] == null){
		$sql = "INSERT INTO userdetails (userID, fullName, email, phoneNumber) VALUES ('$userID','$fullName', '$email', '$phoneNumber')";
	} else {
		$sql = "UPDATE userdetails SET fullName = '$fullName', email = '$email', phoneNumber = '$phoneNumber' WHERE userID = '$userID'";
	}
	if (mysqli_query($link, $sql)) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($link);
	}
	echo "<meta http-equiv='refresh' content='0'>";
}

if(isset($_POST['vehicleBtn'])){
	$carBrand = $_POST["carBrand"];
	$carName = $_POST["carName"];
	$numberPlate = strtoupper($_POST["numPlate"]);
	$carName = ($carName ?: $vehicleDetails["carName"]);
	$numberPlate = ($numberPlate ?: $vehicleDetails["numberPlate"]);
	if ($vehicleDetails["carName"] == null && $vehicleDetails["numberPlate"] == null){
		$sql = "INSERT INTO usercar (carID, carBrand, carName, numberPlate) VALUES ('$userID', '$carBrand', '$carName', '$numberPlate')";
	} else {
		$sql = "UPDATE usercar SET carBrand = '$carBrand', carName = '$carName', numberPlate = '$numberPlate' WHERE carID = '$userID'";
	}
	if (mysqli_query($link, $sql)) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($link);
	}
	echo "<meta http-equiv='refresh' content='0'>";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>WeFix</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" 
integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<style>
img.header {
	object-fit: cover;
	width: 100%;
	max-height: 50vh;
	overflow: hidden;
}

.imageContainer {
	position: relative;
	text-align: center;
}

.centered {
	position: absolute;
	background-color: rgba(13, 13, 13, 0.5);
	color: white;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}

.headerText {
	font-size: 8vmin;
}
</style>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <a class="navbar-brand">WeFix</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	  	<span class="navbar-toggler-icon"></span>
	  </button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
    		<div class="navbar-nav mr-auto">
        		<a class="nav-item nav-link" href="index.php">Home</a>
        		<a class="nav-item active nav-link" href="userPage.php">Details</a>
        		<a class="nav-item nav-link" href="service.php">Booking</a>
        	</div>
        	<a href="logout.php"><button class="btn btn-primary mr-1">Logout</button></a>
        	<a href="resetPassword.php"><button class="btn btn-primary mr-1">Reset Password</button></a>
		</div>
	</nav>
	<div class="container-fluid min-vh-100">
		<div class="row imageContainer">
			<img class="header" src="Images/mechanic.jfif">
			<div class="centered headerText display-2">Details</div>
		</div>
		<div class="row">
			<!-- Input -->
			<form class="col-sm border bg-light" action="" method="POST">
				<p class="headerText display-4">Update Vehicle Details</p>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
				   		<span class="input-group-text">Car Brand</span>
				   	</div>
				   	<input  class="form-control form-control-lg" name="carBrand">
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
				   		<span class="input-group-text">Car Name</span>
				   	</div>
				   	<input class="form-control form-control-lg" name="carName">
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
				   		<span class="input-group-text">Number Plate</span>
				   	</div>
					<input class="form-control form-control-lg" name="numPlate">
				</div>
				<span class="help-block"><?php echo $message; ?></span>
				<button type="submit" class="btn btn-primary btn-lg mb-1" name="vehicleBtn" value="Submit">Submit</button>
			</form>
			<form class="col-sm border bg-light" action="" method="POST">
				<p class="headerText display-4">Update Contact Details</p>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
				   		<span class="input-group-text">Full Name</span>
				   	</div>
					<input class="form-control form-control-lg" name="fullName">
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
				   		<span class="input-group-text">Email</span>
				   	</div>
					<input class="form-control form-control-lg" name="email">
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
				   		<span class="input-group-text">Phone Number</span>
				   	</div>
					<input class="form-control form-control-lg" name="phoneNumber">
				</div>
				<span class="help-block"></span>
				<button type="submit" class="btn btn-primary btn-lg mb-1" name="contactBtn" value="Submit">Submit</button>
			</form>
		</div>
		<div class="row">
			<!-- Output -->
			<form class="col-sm border bg-light">
				<p class="headerText display-4">Current Details</p>
				<div class="form-group">
				    <label for="numPlate">Full Name</label>
					<input class="form-control form-control-lg" value="<?php echo $userDetails["fullName"]; ?>" readonly>
				</div>
				<div class="form-group">
				    <label for="numPlate">Email Address</label>
					<input class="form-control form-control-lg" value="<?php echo $userDetails["email"]; ?>" readonly>
				</div>
				<div class="form-group">
				    <label for="numPlate">Phone Number</label>
					<input class="form-control form-control-lg" value="<?php echo $userDetails["phoneNumber"]; ?>" readonly>
				</div>
				<div class="form-group">
				    <label for="carName">Car Name</label>
					<input class="form-control form-control-lg" value="<?php echo $vehicleDetails["carName"]; ?>" readonly>
				</div>
				<div class="form-group">
				    <label for="numPlate">Number Plate</label>
					<input class="form-control form-control-lg" value="<?php echo $vehicleDetails["numberPlate"]; ?>" readonly>
				</div>
			</form>
		</div>
	</div>
</body>