<?php
// Startup files
require_once "config.php";
$username = $password = $confirm_password = $message = "";
$username_err = $password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$fullName = $_POST["fullName"];
	$email = $_POST["email"];
	$phoneNumber = $_POST["phoneNumber"];
	$carBrand = $_POST["carBrand"];
	$carName = $_POST["carName"];
	$numberPlate = strtoupper($_POST["numPlate"]);
	$service = $_POST["service"];
	$serviceProgress = "Booked";
	$serviceDate = $_POST["serviceDate"];
	$serviceTime = $_POST["serviceTime"];
	$location = $_POST["location"];
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM useraccount WHERE username = ?";
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
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
	// Validate password
	if(empty(trim($_POST["password"]))){
	    $password_err = "Please enter a password.";     
	} elseif(strlen(trim($_POST["password"])) < 6){
	    $password_err = "Password must have atleast 6 characters.";
	} else{
	    $password = trim($_POST["password"]);
	}
	// Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO useraccount (username, password) VALUES (?, ?)";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
            	echo "Record successfully added";
            } else{
                echo "Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // get ID from username
	$query = "SELECT id FROM useraccount WHERE username = '$username'";
	$userID = $link->query($query);
	$row = mysqli_fetch_assoc($userID);
	$userID = $row["id"];
    // contact Details
    if(preg_match("/^[0-9]{5} [0-9]{6}$/", $phoneNumber)) {
		$sql = "INSERT INTO userdetails (userID, fullName, email, phoneNumber) VALUES ('$userID','$fullName', '$email', '$phoneNumber')";
		if (mysqli_query($link, $sql)) {
		} else {
			$message = "Error: Invalid user details";
		}
		// car Details
		$sql = "INSERT INTO usercar (carID, carBrand, carName, numberPlate) VALUES ('$userID','$carBrand', '$carName', '$numberPlate')";
		if (mysqli_query($link, $sql)) {
		} else {
			$message = "Error: Invalid car details";
		}
		// service Details
		$sql = "INSERT INTO userservice (customerID, service, serviceProgress, serviceDate, serviceTime, location) VALUES ('$userID','$service', '$serviceProgress', '$serviceDate', '$serviceTime', '$location')";
		if (mysqli_query($link, $sql)) {
		} else {
			$message = "Error: Invalid service details";
		}
	} else{
		$message = "Invalid phone Number";
	}
	// Close connection
	mysqli_close($link);
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
        		<a class="nav-item nav-link" href="admin.php">Admin</a>
        		<a class="nav-item active nav-link" href="report.php">Report</a>
        		<a class="nav-item nav-link" href="parts.php">Parts</a>
        		<a class="nav-item nav-link" href="invoice.php">Invoice</a>
        	</div>
		</div>
	</nav>
	<div class="container-fluid min-vh-100">
		<div class="row imageContainer">
			<img class="header" src="Images/mechanic.jfif">
			<div class="centered headerText display-2">Registration Page</div>
		</div>
		<div class="row">
			<form class="col-sm border bg-light" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
				<p class="headerText display-4">Customer Details</p>
				<div class="row">
					<div class="form-group col-sm <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
					    <label>Username</label>
						<input class="form-control" name="username">
						<span class="help-block"><?php echo $username_err; ?></span>
					</div>
					<div class="form-group col-sm <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
					    <label>Password</label>
						<input type="password" class="form-control" name="password">
						<span class="help-block"><?php echo $password_err; ?></span>
					</div>
					<div class="form-group col-sm <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
					    <label>Confirm Password</label>
						<input type="password" class="form-control" name="confirm_password">
						<span class="help-block"><?php echo $confirm_password_err; ?></span>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm">
					    <label>Full Name</label>
						<input class="form-control" name="fullName">
					</div>
					<div class="form-group col-sm">
					    <label>Email Address</label>
						<input class="form-control" name="email">
					</div>
					<div class="form-group col-sm">
					    <label>Phone Number</label>
						<input class="form-control" name="phoneNumber">
					</div>
				</div>
				<div class="row mb-3">
					<div class="form-group col-sm">
					    <label>Car Brand</label>
						<input class="form-control" name="carBrand">
					</div>
					<div class="form-group col-sm">
					    <label>Car Name</label>
						<input class="form-control" name="carName">
					</div>
					<div class="form-group col-sm">
					    <label>Number Plate</label>
						<input class="form-control" name="numPlate">
					</div>
				</div>
				<div class="row">
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
				<div class="row">
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
				<button type="submit" name="saveBtn" class="btn btn-primary btn-lg mb-2">Submit</button>
				<span><?php echo $message ?></span>
			</form>
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