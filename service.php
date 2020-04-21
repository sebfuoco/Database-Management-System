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

$message = $previousHistory = "" ;
$query = "SELECT service, serviceProgress, serviceDate, serviceTime, location FROM userservice WHERE customerID = '$userID'";
$serviceDetails = $link->query($query);
if ($serviceDetails != null){
	$serviceDetails = mysqli_fetch_assoc($serviceDetails);
}
$query="SELECT service, serviceDate, serviceTime, location, price, summary FROM userprevioushistory WHERE userID = '$userID'";
$result = $link->query($query);
$i = 0;
while($row = mysqli_fetch_assoc($result)){
	$i++;
    $previousHistory.= $i." | Service: ".$row["service"]." on ".$row["serviceDate"]." at ".$row["serviceTime"]." in ".$row["location"].". Cost: £".$row["price"]." Summary: ".$row["summary"]."\n";
}
if(isset($_POST['bookingBtn'])){ //check if form was submitted
	$service = $_POST["service"];
	$serviceProgress = "Booked";
	$serviceDate = $_POST["serviceDate"];
	$serviceTime = $_POST["serviceTime"];
	$location = $_POST["location"];
	if ($serviceDetails["service"] == null && $serviceDetails["serviceProgress"] == null){
		$sql = "INSERT INTO userservice (customerID, service, serviceProgress, serviceDate, serviceTime, location) VALUES ('$userID','$service', '$serviceProgress', '$serviceDate', '$serviceTime', '$location')";
		if (mysqli_query($link, $sql)) {
			$message = "New record created successfully";
		} else {
			$message = "Error: " . $sql . "<br>" . mysqli_error($link);
		}
	} else {
		$message = "You cannot have multiple services at one time.";
	}
	echo "<meta http-equiv='refresh' content='0'>";
}
if(isset($_POST['removeBtn'])){
	$sql = "DELETE FROM userservice WHERE customerID = $userID";
	if ($link->query($sql) === TRUE) {
	    echo "Record deleted successfully";
	} else {
	    echo "Error deleting record: " . $link->error;
	}
	echo "<meta http-equiv='refresh' content='0'>";
}
if(isset($_POST['changeBtn'])){
	$service = $_POST["modalService"];
	$serviceDate = $_POST["modalDate"];
	$serviceTime = $_POST["modalTime"];
	$location = $_POST["modalLocation"];
	$sql = "UPDATE userservice SET service = '$service', serviceDate = '$serviceDate', serviceTime = '$serviceTime', location = '$location' WHERE customerID = '$userID'";
	if (mysqli_query($link, $sql)) {
			echo "New record updated successfully";
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
        		<a class="nav-item nav-link" href="userPage.php">Details</a>
        		<a class="nav-item active nav-link" href="service.php">Booking</a>
        	</div>
        	<a href="logout.php"><button class="btn btn-primary mr-1">Logout</button></a>
        	<a href="resetPassword.php"><button class="btn btn-primary mr-1">Reset Password</button></a>
		</div>
	</nav>
	<div class="container-fluid min-vh-100">
		<div class="row imageContainer">
			<img class="header" src="Images/mechanic.jfif">
			<div class="centered headerText display-2">Bookings</div>
		</div>
		<div class="row">
			<form class="col-sm border bg-light" action="" method="POST">
				<p class="headerText display-4">Book Service</p>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
				   		<span class="input-group-text">Service</span>
				   	</div>
					<select class="form-control form-control-lg" name="service">
						<option>MOT Test</option>
						<option>Repair</option>
					</select>
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text">Day</span>
					</div>
					<select class="form-control form-control-lg" name="serviceDate">
						<option name="day"></option>
						<option name="day"></option>
						<option name="day"></option>
						<option name="day"></option>
						<option name="day"></option>
						<option name="day"></option>
						<option name="day"></option>
					</select>
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text">Time</span>
					</div>
					<select class="form-control form-control-lg" id="serviceTime" name="serviceTime"></select>
				</div>
				<div class="input-group mb-3">
					<div class="input-group-prepend">
						<span class="input-group-text">Location</span>
					</div>
					<select class="form-control form-control-lg" name="location">
						<option>Woking</option>
						<option>Farnborough</option>
						<option>Guildford</option>
						<option>Camberley</option>
						<option>Reading</option>
						<option>Swindon</option>
						<option>Oxford</option>
					</select>
				</div>
				<span class="help-block"><?php echo $message; ?></span>
				<button type="submit" name="bookingBtn" class="btn btn-primary btn-lg mb-1">Submit</button>
			</form>
			<!-- Output -->
			<form class="col-sm border bg-light">
				<p class="headerText display-4">Current Progress</p>
				<div class="row justify-content-center">
					<div class="form-group col-sm">
						<label for="progress">Current Service</label>
						<input class="form-control form-control-lg" value="<?php echo $serviceDetails["service"]; ?>" readonly>
					</div>
					<div class="form-group col-sm">
						<label for="progress">Service Progress</label>
						<input class="form-control form-control-lg" value="<?php echo $serviceDetails["serviceProgress"]; ?>" readonly>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="form-group col-sm">
						<label for="progress">Day</label>
						<input class="form-control form-control-lg" value="<?php echo $serviceDetails["serviceDate"]; ?>" readonly>
					</div>
					<div class="form-group col-sm">
						<label for="progress">Time</label>
						<input class="form-control form-control-lg" value="<?php echo $serviceDetails["serviceTime"]; ?>" readonly>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="form-group col-sm">
						<label for="progress">Location</label>
						<input class="form-control form-control-lg" value="<?php echo $serviceDetails["location"]; ?>" readonly>
					</div>
				</div>
				<button type="button" data-toggle="modal" data-target="#currentService" class="btn btn-primary btn-lg mb-1">Change current service</button>
			</form>
		</div>
		<div class="row">
			<form class="col-sm border bg-light">
				<p class="headerText display-4">Previous History</p>
				<div class="form-group">
					<textarea class="form-control form-control-lg" readonly><?php echo $previousHistory; ?></textarea>
				</div>
			</form>
		</div>
	</div>
	<div id="currentService" class="modal fade">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Amend progress</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			        	<span aria-hidden="true">&times;</span>
			        </button>
				</div>
				<div class="modal-body">
					<form action="" method="POST">
						<div class="form-group form-row align-items-center">
							<div class="input-group mb-3">
					        	<div class="input-group-prepend">
					          		<div class="input-group-text">Service</div>
					        	</div>
						     	<select class="form-control form-control-lg" name="modalService">
									<option>MOT Test</option>
									<option>Repair</option>
								</select>
						    </div>
						    <div class="input-group mb-3">
						        <div class="input-group-prepend">
						          	<div class="input-group-text">Day</div>
						        </div>
					    		<select class="form-control form-control-lg" name="modalDate">
									<option name="dayModal"></option>
									<option name="dayModal"></option>
									<option name="dayModal"></option>
									<option name="dayModal"></option>
									<option name="dayModal"></option>
									<option name="dayModal"></option>
									<option name="dayModal"></option>
								</select>
					    	</div>
					    	<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">Time</span>
								</div>
								<select class="form-control form-control-lg" id="modalTime" name="modalTime"></select>
							</div>
							<div class="input-group mb-3">
								<div class="input-group-prepend">
									<span class="input-group-text">Location</span>
								</div>
								<select class="form-control form-control-lg" name="modalLocation">
									<option>Woking</option>
									<option>Farnborough</option>
									<option>Guildford</option>
									<option>Camberley</option>
									<option>Reading</option>
									<option>Swindon</option>
									<option>Oxford</option>
								</select>
							</div>
					    </div>
				</div>
				    	<div class="modal-footer">
				    		<button class="btn btn-danger" name="removeBtn">Remove</button>
				    		<button class="btn btn-primary" name="changeBtn">Submit Changes</button>
				    	</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<script>
// Function from https://medium.com/@quynh.totuan/how-to-get-the-current-week-in-javascript-9e64d45a9a08
var curr = new Date();
var week = [];
var start = 0;
for (let i = 0; i <= 6; i++) {
	let first = curr.getDate() + start;
	start = 1;
	let day = new Date(curr.setDate(first)).toISOString().slice(0, 10);
	week.push(day);
}
// Function from https://stackoverflow.com/a/13459946
function convertDate(inputFormat) {
	function pad(s) { return (s < 10) ? '0' + s : s; }
 	var d = new Date(inputFormat)
  	return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('/')
} 

for (var i = 0; i < week.length; i++){
	let day = convertDate(week[i]);
	document.getElementsByName("day")[i].innerHTML = day;
	document.getElementsByName("dayModal")[i].innerHTML = day;
}

var today = new Date();
var time = today.getHours();
console.log(time);
var timeRange = [9, 10, 11, 12, 13, 14, 15, 16, 17];
var startTime = timeRange.indexOf(time);
var select = document.getElementById("serviceTime");
for (var i = 0; startTime < timeRange.length; startTime++ && i++){
	var option = document.createElement("option");
    option.text = timeRange[startTime] + ":00";
    select.appendChild(option);
}
startTime = timeRange.indexOf(time);
var select = document.getElementById("modalTime");
for (var i = 0; startTime < timeRange.length; startTime++ && i++){
	var option = document.createElement("option");
    option.text = timeRange[startTime] + ":00";
    select.appendChild(option);
}
</script>
</body>
