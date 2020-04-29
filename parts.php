<?php
require_once "config.php";
$message = $warning = "";
$sql = "SHOW COLUMNS FROM vehiclepartstock";
$result = mysqli_query($link,$sql);
$parts= '<select name="parts">';
$parts.='<option>'."None".'</option>';
while($row = mysqli_fetch_array($result)){
	if ($row["Field"] != "company"){
    	$parts.='<option>'.$row['Field'].'</option>';
	}
}
$query="SELECT id, username FROM useraccount";
$result = $link->query($query);
$username= '<select name="username">';
while($row = mysqli_fetch_assoc($result)){
   	$username.='<option value="'.$row['id'].'">'.$row['username'].'</option>';
}
$query="SELECT company FROM vehiclepartstock";
$result = $link->query($query);
$companyPart= '<select name="company">';
while($row = mysqli_fetch_assoc($result)){
   	$companyPart.='<option>'.$row['company'].'</option>';
}
// Warn if original parts are low
$query="SELECT engine, battery, brakes, wiperblades, bulbs FROM vehiclepartstock";
$result = $link->query($query);
$result = mysqli_fetch_assoc($result);
switch($result["engine"]){
	case ($result["engine"] < 2):
		$warning.= "Resupply original engine now! ";
		break;
	case ($result["engine"] < 4):
		$message.= "Original engine supply is low! ";
		break;
}
switch($result["battery"]){
	case ($result["battery"] < 2):
		$warning.= "Resupply original batteries now! ";
		break;
	case ($result["battery"] < 4):
		$message.= "Original battery supply is low! ";
		break;
}
switch($result["brakes"]){
	case ($result["brakes"] < 4):
		$warning.= "Resupply original brakes now! ";
		break;
	case ($result["brakes"] < 12):
		$message.= "Original brakes supply is low! ";
		break;
}
switch($result["wiperblades"]){
	case ($result["wiperblades"] < 2):
		$warning.= "Resupply original wiperblades now! ";
		break;
	case ($result["wiperblades"] < 4):
		$message.= "Original wiperblades supply is low! ";
		break;
}
switch($result["bulbs"]){
	case ($result["bulbs"] < 2):
		$warning.= "Resupply original bulbs now! ";
		break;
	case ($result["bulbs"] < 8):
		$message.= "Original bulbs supply is low! ";
		break;
}
// Warn if substitute parts are low
$query2="SELECT engine, battery, brakes, wiperblades, bulbs FROM vehiclesubpartstock";
$result = $link->query($query);
$result = mysqli_fetch_assoc($result);
switch($result["engine"]){
	case ($result["engine"] < 2):
		$warning.= "Resupply substitute engine now! ";
		break;
	case ($result["engine"] < 4):
		$message.= "Substitute engine supply is low! ";
		break;
}
switch($result["battery"]){
	case ($result["battery"] < 2):
		$warning.= "Resupply substitute batteries now! ";
		break;
	case ($result["battery"] < 4):
		$message.= "Substitute battery supply is low! ";
		break;
}
switch($result["brakes"]){
	case ($result["brakes"] < 4):
		$warning.= "Resupply substitute brakes now! ";
		break;
	case ($result["brakes"] < 12):
		$message.= "Substitute brakes supply is low! ";
		break;
}
switch($result["wiperblades"]){
	case ($result["wiperblades"] < 2):
		$warning.= "Resupply substitute wiperblades now! ";
		break;
	case ($result["wiperblades"] < 4):
		$message.= "Substitute wiperblades supply is low! ";
		break;
}
switch($result["bulbs"]){
	case ($result["bulbs"] < 2):
		$warning.= "Resupply substitute bulbs now! ";
		break;
	case ($result["bulbs"] < 8):
		$message.= "Substitute bulbs supply is low! ";
		break;
}
if(isset($_POST['submitBtn'])){
	$userID = $_POST["username"];
	$parts = $_POST["parts"];
	$quantity = $_POST["quantity"];
	$hours = $_POST["hours"];
	$rate = $_POST["rate"];
	$summary = $_POST["summary"];
	if($quantity == ""){$quantity = "0";}
    // contact Details
    if(is_numeric($hours) && is_numeric($quantity) && is_numeric($rate)) {
		$sql = "INSERT INTO vehiclepart (partID, part, quantity, hours, rate, summary) VALUES ('$userID','$parts', '$quantity', '$hours', '$rate', '$summary')";
		if (mysqli_query($link, $sql)) {
			$query = "SELECT carBrand FROM usercar WHERE carID = '$userID'";
			$result = $link->query($query);
			$company = mysqli_fetch_assoc($result);
			$company = $company["carBrand"];
			$query="SELECT engine, battery, brakes, wiperblades, bulbs FROM vehiclepartstock";
			$query2="SELECT engine, battery, brakes, wiperblades, bulbs FROM vehiclesubpartstock";
			$result = $link->query($query);
			$result2 = $link->query($query2);
			$row = mysqli_fetch_assoc($result);
			$row2 = mysqli_fetch_assoc($result2);
			$query = "UPDATE userservice SET serviceProgress = 'In Progress' WHERE customerID = '$userID'";
			if (mysqli_query($link, $query)) {
			} else {
				echo "Error: " . $link->error;
			}
			switch($parts){
				case "engine":
					if ($quantity <= $row["engine"]){
						$query = "UPDATE vehiclepartstock SET engine = engine - '$quantity' WHERE company = '$company'";
					}
					else if ($quantity <= $row2["engine"]){
						$query = "UPDATE vehiclesubpartstock SET engine = engine - '$quantity' WHERE company = '$company'";
					}
					break;
				case "battery":
					if ($quantity <= $row["battery"]){
						$query = "UPDATE vehiclepartstock SET battery = battery - '$quantity' WHERE company = '$company'";
					}
					else if($quantity <= $row2["battery"]){
						$query = "UPDATE vehiclesubpartstock SET battery = battery - '$quantity' WHERE company = '$company'";
					}
					break;
				case "brakes":
					if ($quantity <= $row["brakes"]){
						$query = "UPDATE vehiclepartstock SET brakes = brakes - '$quantity' WHERE company = '$company'";
					}
					else if($quantity <= $row2["brakes"]){
						$query = "UPDATE vehiclesubpartstock SET brakes = brakes - '$quantity' WHERE company = '$company'";
					}
					break;
				case "wiperblades":
					if ($quantity <= $row["wiperblades"]){
						$query = "UPDATE vehiclepartstock SET wiperblades = wiperblades - '$quantity' WHERE company = '$company'";
					}
					else if ($quantity <= $row2["wiperblades"]) {
						$query = "UPDATE vehiclesubpartstock SET engine = engine - '$quantity' WHERE company = '$company'";
					}
					break;
				case "bulbs":
					if ($quantity <= $row["bulbs"]){
						$query = "UPDATE vehiclepartstock SET bulbs = bulbs - '$quantity' WHERE company = '$company'";
					}
					else if($quantity <= $row2["bulbs"]){
						$query = "UPDATE vehiclesubpartstock SET bulbs = bulbs - '$quantity' WHERE company = '$company'";
					}
					break;	
				default:
					break;
			}
			if (mysqli_query($link, $query)) {
			} else {
				echo "Error: " . $link->error;
			}
		} else {
			echo "Error: " . $link->error;
		}
	} else{
		echo "Invalid number";
	}
}
if(isset($_POST['orderBtn'])){
	$company = $_POST["company"];
	$part = $_POST["orderParts"];
	$sub = $_POST["sub"];
	$quantity = $_POST["orderQuantity"];
	switch($part){
		case "engine":
			if($sub == "Original"){
				$query = "UPDATE vehiclepartstock SET engine = engine + '$quantity' WHERE company = '$company'";
			} else if ($sub == "Substitute"){
				$query = "UPDATE vehiclesubpartstock SET engine = engine + '$quantity' WHERE company = '$company'";
			}
			break;
		case "battery":
			if($sub == "Original"){
				$query = "UPDATE vehiclepartstock SET battery = battery + '$quantity' WHERE company = '$company'";
			} else if ($sub == "Substitute"){
				$query = "UPDATE vehiclesubpartstock SET battery = battery + '$quantity' WHERE company = '$company'";
			}
			break;
		case "brakes":
			if($sub == "Original"){
				$query = "UPDATE vehiclepartstock SET brakes = brakes + '$quantity' WHERE company = '$company'";
			} else if ($sub == "Substitute"){
				$query = "UPDATE vehiclesubpartstock SET brakes = brakes + '$quantity' WHERE company = '$company'";
			}
			break;
		case "wiperblades":
			if($sub == "Original"){
				$query = "UPDATE vehiclepartstock SET wiperblades = wiperblades + '$quantity' WHERE company = '$company'";
			} else if ($sub == "Substitute"){
				$query = "UPDATE vehiclesubpartstock SET wiperblades = wiperblades + '$quantity' WHERE company = '$company'";
			}
			break;
		case "bulbs":
			if($sub == "Original"){
				$query = "UPDATE vehiclepartstock SET bulbs = bulbs + '$quantity' WHERE company = '$company'";
			} else if ($sub == "Substitute"){
				$query = "UPDATE vehiclesubpartstock SET bulbs = bulbs + '$quantity' WHERE company = '$company'";
			}
			break;	
		default:
			break;
	}
	if (mysqli_query($link, $query)) {
	} else {
		echo "Error: " . $link->error;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
<title>WeFix</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" 
integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="format.css">
</head>
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
        		<a class="nav-item nav-link" href="report.php">Report</a>
        		<a class="nav-item active nav-link" href="parts.php">Parts</a>
        		<a class="nav-item nav-link" href="invoice.php">Invoice</a>
        	</div>
		</div>
	</nav>
	<div class="container-fluid min-vh-100">
		<div class="row imageContainer">
			<img class="header" src="Images/mechanic.jfif">
			<div class="centered headerText display-2">Parts Page</div>
		</div>
		<div class="row">
			<form class="col-sm border bg-light" action="" method="POST">
				<h4 class="row bg-warning mb-0 warning"><?php echo $message; ?></h4>
				<h4 class="row bg-danger mb-0 warning"><?php echo $warning; ?></h4>
				<p class="headerText display-4">Part Details</p>
				<div class="row">
					<div class="form-group col-sm">
						<label>Part</label>
						<select class="form-control form-control-lg" name="parts" value="<?php echo $parts ?>">
						</select>
					</div>
					<div class="form-group col-sm">
						<label>Quantity</label>
						<input class="form-control form-control-lg" name="quantity">
						</select>
					</div>
				</div>
				<p class="headerText display-4">Work Details</p>
				<div class="row">
					<div class="form-group col-sm">
						<label>Username</label>
						<select class="form-control form-control-lg" name="username" value="<?php echo $username ?>"></select>
					</div>
					<div class="form-group col-sm">
						<label>Hours</label>
						<input class="form-control form-control-lg" name="hours">
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm">
						<label>Rate</label>
						<input class="form-control form-control-lg" name="rate">
					</div>
					<div class="form-group col-sm">
						<label>Summary</label>
						<input class="form-control form-control-lg" name="summary">
					</div>
				</div>
				<button type="submit" name="submitBtn" class="btn btn-primary btn-lg mb-1">Submit</button>
				<p class="headerText display-4">Order Parts</p>
				<div class="row">
					<div class="form-group col-sm">
						<label>Company</label>
						<select class="form-control form-control-lg" name="company" value="<?php echo $companyPart ?>">
						</select>
					</div>
					<div class="form-group col-sm">
						<label>Part</label>
						<select class="form-control form-control-lg" name="orderParts" value="<?php echo $parts ?>">
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm">
						<label>Type</label>
						<select class="form-control form-control-lg" name="sub">
							<option>Original</option>
							<option>Substitute</option>
						</select>
					</div>
					<div class="form-group col-sm">
						<label>Quantity</label>
						<input class="form-control form-control-lg" name="orderQuantity">
					</div>
				</div>
				<button type="submit" name="orderBtn" class="btn btn-primary btn-lg mb-2">Order</button>
			</form>
		</div>
	</div>
</body>