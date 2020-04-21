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

.smallText {
	font-size: 6vmin;
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
        		<a class="nav-item active nav-link" href="index.php">Home</a>
        		<a class="nav-item nav-link" href="userPage.php">Details</a>
        		<a class="nav-item nav-link" href="service.php">Booking</a>
        	</div>
        	<a href="register.php"><button class="btn btn-primary mr-1 mb-1" id="signUp">Sign Up</button></a>
        	<a href="login.php"><button class="btn btn-primary mr-1 mb-1" id="login">Login</button></a>
        	<a href="logout.php"><button class="btn btn-primary mr-1 mb-1" id="logout">Logout</button></a>
        	<a href="resetPassword.php"><button class="btn btn-primary mr-1 mb-1" id="resetPassword">Reset Password</button></a>
		</div>
	</nav>
	<div class="container-fluid min-vh-100">
		<div class="row imageContainer">
			<img class="header" src="Images/mechanic.jfif">
			<div class="centered headerText display-2">Welcome to WeFix Garage</div>
		</div>
		<div class="row justify-content-center bg-info">
			<div class="col p-3 text-center">
				<p class="display-4 headerText">Book a service a week in advance</p>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col p-3 text-center">
			<p class="display-2 headerText">Why Us?</p>
			<h1 class="smallText lead">With millions of satisfied customers, we at WeFix Garage will fix your vehicle for an affordable price. With online booking you can request a service from us straight away and come to a time and place thats best suited for you. View your current service progress online and change if need be!</h1>
		</div>
		</div>
	</div>
</body>
<script>
function changeButton(showLogin){
	var logout = document.getElementById("logout");
	var resetPassword = document.getElementById("resetPassword");
	var login = document.getElementById("login");
	var signUp = document.getElementById("signUp");
	if (showLogin == 0){
		logout.style.display = "none";
		resetPassword.style.display = "none";
		login.style.display = "block";
		signUp.style.display = "block";
	}
	else {
		logout.style.display = "block";
		resetPassword.style.display = "block";
		login.style.display = "none";
		signUp.style.display = "none";
	}
}
</script>
<!-- PHP sign in code from https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php -->
<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    echo "you are not logged in";
    echo '<script type="text/javascript"> changeButton(0); </script>';
}
else {
	echo '<script type="text/javascript"> changeButton(1); </script>';
}
?>