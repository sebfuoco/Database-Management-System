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
$message = $name = $textbox1 = $textbox2 = $textbox3 = $textbox4 = $textbox5 = "";
$label1 = $label2 = $label3 = $label4 = $label5 = ""; 

$query = "SELECT fullName, email, phoneNumber FROM userdetails";
$result = $link->query($query);
$query="SELECT id, username FROM useraccount";
$result = $link->query($query);
$username= '<select name="username">';
while($row = mysqli_fetch_assoc($result)){
    $username.='<option value="'.$row['id'].'">'.$row['username'].'</option>';
}
if(isset($_POST['removeBtn1'])){
	$userID = $_POST["username1"];
	$sql = "DELETE FROM usercar WHERE carID = $userID";
	if ($link->query($sql) === TRUE) {
	    echo "Record deleted successfully";
	} else {
	    echo "Error deleting record: " . $link->error;
	}
	echo "<meta http-equiv='refresh' content='0'>";
}
if(isset($_POST['removeBtn2'])){
	$userID = $_POST["username2"];
	$sql = "DELETE FROM userdetails WHERE userID = $userID";
	if ($link->query($sql) === TRUE) {
	    echo "Record deleted successfully";
	} else {
	    echo "Error deleting record: " . $link->error;
	}
	echo "<meta http-equiv='refresh' content='0'>";
}
if(isset($_POST['removeBtn3'])){
	$userID = $_POST["username3"];
	$sql = "DELETE FROM userservice WHERE customerID = $userID";
	if ($link->query($sql) === TRUE) {
	    echo "Record deleted successfully";
	} else {
	    echo "Error deleting record: " . $link->error;
	}
	echo "<meta http-equiv='refresh' content='0'>";
}
if(isset($_POST['viewBtn1'])){
	$userID = $_POST["username1"];
	$query = "SELECT username FROM useraccount WHERE id = '$userID'";
	$result = $link->query($query);
	$result = mysqli_fetch_assoc($result);
	$name = $result["username"];
	$query = "SELECT carBrand, carName, numberPlate FROM usercar WHERE carID = '$userID'";
	$result = $link->query($query);
	if ($result != null){
		$result = mysqli_fetch_assoc($result);
	}
	$sql = "SHOW COLUMNS FROM usercar";
	$result2 = mysqli_query($link,$sql);
	while($row = mysqli_fetch_array($result2)){
	     $array[] = $row['Field'];
	}
	$textbox1 = $result["carBrand"];
	$textbox2 = $result["carName"];
	$textbox3 = $result["numberPlate"];
	$label1 = $array[2];
	$label2 = $array[3];
	$label3 = $array[4];
}
if(isset($_POST['viewBtn2'])){
	$userID = $_POST["username2"];
	$query = "SELECT username FROM useraccount WHERE id = '$userID'";
	$result = $link->query($query);
	$result = mysqli_fetch_assoc($result);
	$name = $result["username"];
	$query = "SELECT fullName, email, phoneNumber FROM userdetails WHERE userID = '$userID'";
	$result = $link->query($query);
	if ($result != null){
		$result = mysqli_fetch_assoc($result);
	}
	$sql = "SHOW COLUMNS FROM userdetails";
	$result2 = mysqli_query($link,$sql);
	while($row = mysqli_fetch_array($result2)){
	     $array[] = $row['Field'];
	}
	$textbox1 = $result["fullName"];
	$textbox2 = $result["email"];
	$textbox3 = $result["phoneNumber"];
	$label1 = $array[2];
	$label2 = $array[3];
	$label3 = $array[4];
}
if(isset($_POST['viewBtn3'])){
	$userID = $_POST["username3"];
	$query = "SELECT username FROM useraccount WHERE id = '$userID'";
	$result = $link->query($query);
	$result = mysqli_fetch_assoc($result);
	$name = $result["username"];
	$query = "SELECT service, serviceProgress, serviceDate, serviceTime, location FROM userservice WHERE customerID = '$userID'";
	$result = $link->query($query);
	if ($result != null){
		$result = mysqli_fetch_assoc($result);
	}
	$sql = "SHOW COLUMNS FROM userservice";
	$result2 = mysqli_query($link,$sql);
	while($row = mysqli_fetch_array($result2)){
	     $array[] = $row['Field'];
	}
	$textbox1 = $result["service"];
	$textbox2 = $result["serviceProgress"];
	$textbox3 = $result["serviceDate"];
	$textbox4 = $result["serviceTime"];
	$textbox5 = $result["location"];
	$label1 = $array[2];
	$label2 = $array[3];
	$label3 = $array[4];
	$label4 = $array[5];
	$label5 = $array[6];
}
if(isset($_POST['vehicleBtn'])){
	$userID = $_POST["username1"];
	$carBrand = $_POST["carBrand"];
	$carName = $_POST["carName"];
	$numberPlate = strtoupper($_POST["numPlate"]);

	$sql = "UPDATE usercar SET carBrand = '$carBrand', carName = '$carName', numberPlate = '$numberPlate' WHERE carID = '$userID'";
	if (mysqli_query($link, $sql)) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($link);
	}
	echo "<meta http-equiv='refresh' content='0'>";
}  
if(isset($_POST['contactBtn'])){ //check if form was submitted
	$userID = $_POST["username2"];
	$fullName = $_POST["fullName"];
	$email = $_POST["email"];
	$phoneNumber = $_POST["phoneNumber"];

	if(preg_match("/^[0-9]{5} [0-9]{6}$/", $phoneNumber)) {
		echo "Gucci";
	}
	$sql = "UPDATE userdetails SET fullName = '$fullName', email = '$email', phoneNumber = '$phoneNumber' WHERE userID = '$userID'";
	if (mysqli_query($link, $sql)) {
		echo "New record created successfully";
	} else {
		echo "Error: " . $sql . "<br>" . mysqli_error($link);
	}
	echo "<meta http-equiv='refresh' content='0'>";
}
if(isset($_POST['bookingBtn'])){
	$userID = $_POST["username3"];
	$service = $_POST["service"];
	$serviceDate = $_POST["serviceDate"];
	$serviceTime = $_POST["serviceTime"];
	$location = $_POST["location"];
	$sql = "UPDATE userservice SET service = '$service', serviceDate = '$serviceDate', serviceTime = '$serviceTime', location = '$location' WHERE customerID = '$userID'";
	if (mysqli_query($link, $sql)) {
			echo "New record updated successfully";
		} else {
			echo "Error: " . $sql . "<br>" . mysqli_error($link);
		}
	echo "<meta http-equiv='refresh' content='0'>";
}
if(isset($_POST['updateBtn'])){
	$userID = $_POST["updateUser"];
	$serviceProgress = $_POST["modalProgress"];
	$sql = "UPDATE userservice SET serviceProgress = '$serviceProgress' WHERE customerID = '$userID'";
	if ($link->query($sql) === TRUE) {
	    echo "Progress updated successfully";
	} else {
	    echo "Error deleting record: " . $link->error;
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
<link rel="stylesheet" type="text/css" href="format.css">
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
	  <a class="navbar-brand" href="index.php">WeFix</a>
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	  	<span class="navbar-toggler-icon"></span>
	  </button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
    		<div class="navbar-nav mr-auto">
        		<a class="nav-item nav-link" href="index.php">Home</a>
        		<a class="nav-item nav-link" href="userPage.php">Details</a>
        		<a class="nav-item nav-link" href="service.php">Booking</a>
        		<a class="nav-item active nav-link" href="admin.php">Admin</a>
        		<a class="nav-item nav-link" href="report.php">Report</a>
        		<a class="nav-item nav-link" href="parts.php">Parts</a>
        		<a class="nav-item nav-link" href="invoice.php">Invoice</a>
        	</div>
		</div>
	</nav>
	<div class="container-fluid min-vh-100">
		<div class="row imageContainer">
			<img class="header" src="Images/mechanic.jfif">
			<div class="centered headerText display-2">Admin Page</div>
		</div>
		<div class="row">
			<form class="col-sm border bg-light" action="" method="POST">
				<p class="headerText display-4">Update User Vehicle Details</p>
				<div class="form-group">
					<label>Username</label>
					<select class="form-control" name="username1" value="<?php echo $username ?>"></select>
				</div>
				<div class="form-group">
					<label>Car Brand</label>
					<input class="form-control" name="carBrand">
				</div>
				<div class="form-group">
					<label>Car Name</label>
					<input class="form-control" name="carName">
				</div>
				<div class="form-group">
					<label>Number Plate</label>
					<input class="form-control" name="numPlate">
				</div>
				<button type="submit" class="btn btn-primary btn-lg mb-1" name="vehicleBtn" value="Submit">Submit</button>
				<button type="submit" name="viewBtn1" class="btn btn-primary btn-lg mb-1 mr-1 ml-1">View User</button>
				<button class="btn btn-danger btn-lg mb-1 float-right" name="removeBtn1">Remove</button>
			</form>
			<form class="col-sm border bg-light" action="" method="POST">
				<p class="headerText display-4">Update User Contact Details</p>
				<div class="form-group">
					<label>Username</label>
					<select class="form-control" name="username2" value="<?php echo $username ?>"></select>
				</div>
				<div class="form-group">
					<label>Full Name</label>
					<input class="form-control" name="fullName">
				</div>
				<div class="form-group">
					<label>Email Address</label>
					<input class="form-control" name="email">
				</div>
				<div class="form-group">
					<label>Phone Number</label>
					<input class="form-control" name="phoneNumber">
				</div>
				<span class="help-block"></span>
				<button type="submit" class="btn btn-primary btn-lg mb-1" name="contactBtn" value="Submit">Submit</button>
				<button type="submit" name="viewBtn2" class="btn btn-primary btn-lg mb-1 mr-1 ml-1">View User</button>
				<button class="btn btn-danger btn-lg mb-1 float-right" name="removeBtn2">Remove</button>
			</form>
		</div>
		<div class="row">
			<form class="col-sm border bg-light" action="" method="POST">
				<p class="headerText display-4">Update User Service</p>
				<div class="form-group">
					<label>Username</label>
					<select class="form-control" name="username3" value="<?php echo $username ?>"></select>
				</div>
				<div class="row justify-content-center">
					<div class="form-group col-sm">
						<label>Service</label>
						<select class="form-control" name="service">
							<option>MOT Test</option>
							<option>Repair</option>
						</select>
					</div>
					<div class="form-group col-sm">
						<label>Date</label>
						<select class="form-control" name="serviceDate">
							<option name="day"></option>
							<option name="day"></option>
							<option name="day"></option>
							<option name="day"></option>
							<option name="day"></option>
							<option name="day"></option>
							<option name="day"></option>
						</select>
					</div>
				</div>
				<div class="row justify-content-center">
					<div class="form-group col-sm">
						<label>Time</label>
						<select class="form-control" id="serviceTime" name="serviceTime"></select>
					</div>
					<div class="form-group col-sm">
						<label>Location</label>
						<select class="form-control" name="location">
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
				<button type="submit" name="bookingBtn" class="btn btn-primary btn-lg mb-1 mr-1">Submit</button>
				<button type="submit" name="viewBtn3" class="btn btn-primary btn-lg mb-1 mr-1 ml-1">View User</button>
				<button type="button" data-toggle="modal" data-target="#updateProgress" class="btn btn-primary btn-lg mb-1 ml-1">Update Progress</button>
				<button class="btn btn-danger btn-lg mb-1 float-right" name="removeBtn3">Remove</button>
			</form>
	</div>
	<div class="row">
		<form class="col-sm border bg-light" action="" method="POST">
			<p class="headerText display-4">View User Data</p>
			<div class="form-group">
				<label>Username</label>
				<input class="form-control" value="<?php echo $name; ?>" readonly>
			</div>
			<div class="row justify-content-center">
				<div class="form-group col-sm">
					<label><?php echo $label1; ?></label>
					<input class="form-control" value="<?php echo $textbox1; ?>" readonly>
				</div>
				<div class="form-group col-sm">
					<label><?php echo $label2; ?></label>
					<input class="form-control" value="<?php echo $textbox2; ?>" readonly>
				</div>
				<div class="form-group col-sm">
					<label><?php echo $label3; ?></label>
					<input class="form-control" value="<?php echo $textbox3; ?>" readonly>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="form-group col-sm">
					<label><?php echo $label4; ?></label>
					<input class="form-control" value="<?php echo $textbox4; ?>" readonly>
				</div>
				<div class="form-group col-sm">
					<label><?php echo $label5; ?></label>
					<input class="form-control" value="<?php echo $textbox5; ?>" readonly>
				</div>
			</div>
		</form>
	</div>
</div>
<div id="updateProgress" class="modal fade">
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
					<div class="form-group align-items-center">
						<div class="form-group">
							<label>Username</label>
					     	<select class="form-control" name="updateUser" value="<?php echo $username ?>"></select>
					    </div>
						<div class="form-group">
							<label>Progress</label>
					     	<select class="form-control" name="modalProgress">
					     		<option>Booked</option>
								<option>In Progress</option>
								<option>On-Hold</option>
							</select>
					    </div>
					</div>
			    	<div class="modal-footer">
			    		<button class="btn btn-primary" name="updateBtn">Submit</button>
			    	</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
var start = 0;
var today = new Date();
var time = today.getHours();
var timeRange = [9, 10, 11, 12, 13, 14, 15, 16, 17];
if (time > timeRange[8]){
	start = 1;
	var startTime = 0;
}
else if (time < timeRange[0]){
	var startTime = 0;
}
else {
	var startTime = timeRange.indexOf(time);
}
var select = document.getElementById("serviceTime");
for (var i = 0; startTime < timeRange.length; startTime++ && i++){
	var option = document.createElement("option");
    option.text = timeRange[startTime] + ":00";
    select.appendChild(option);
}
// Function from https://medium.com/@quynh.totuan/how-to-get-the-current-week-in-javascript-9e64d45a9a08
var curr = new Date();
var week = [];
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
}

</script>
</body>