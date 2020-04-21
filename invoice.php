<?php
require_once "config.php";
$query="SELECT id, username FROM useraccount";
$result = $link->query($query);
$username= '<select name="username">';
while($row = mysqli_fetch_assoc($result)){
   	$username.='<option value="'.$row['id'].'">'.$row['username'].'</option>';
}

$part = $quantity = $price = $dateResultText = $summary = "";
if(isset($_POST['submitBtn'])){
	$userID = $_POST["username"];
	$query = "SELECT carBrand FROM usercar WHERE carID = '$userID'";
	$result = $link->query($query);
	$company = mysqli_fetch_assoc($result);
	$company = $company["carBrand"];
	$query="SELECT part, quantity, hours, rate, summary FROM vehiclepart WHERE partID = '$userID'";
	$result = $link->query($query);
	$result = mysqli_fetch_assoc($result);
	$part = $result["part"];
	$query2 = "SELECT serviceDate FROM userprevioushistory WHERE userID = '$userID'";
	$query3 = "SELECT serviceDate FROM userservice WHERE customerID = '$userID'";
	$previousDate = $link->query($query2);
	$previousDateResult = mysqli_fetch_assoc($previousDate);
	$date = $link->query($query3);
	$dateResult = mysqli_fetch_assoc($date);
	$partPrice = 1;	
	$dateResultText = $dateResult["serviceDate"];
	$d = date_parse_from_format("d-m-y", $previousDateResult["serviceDate"]);
	$d2 = date_parse_from_format("d-m-y", $dateResult["serviceDate"]);
	$d = date_create($d["year"].'-'.$d["month"].'-'.$d["day"]);
	$d2 = date_create($d2["year"].'-'.$d2["month"].'-'.$d2["day"]);
	$interval = date_diff($d, $d2);
	if (($interval->m + 12*$interval->y) < 6){
		$partPrice = 0;
		$summary.= "Part Price covered by 6 month guarantee! ";
	}
	$query2 = "SELECT enginePrice, batteryPrice, brakesPrice, wiperbladesPrice, bulbsPrice FROM vehiclepartprice WHERE company = '$company'";
	$result2 = $link->query($query2);
	$result2 = mysqli_fetch_assoc($result2);
	switch($part){
		case "engine":
			$price = (($result["hours"] * $result["rate"]) + (($result["quantity"] * $result2["enginePrice"])* $partPrice));
			break;
		case "battery":
			$price = (($result["hours"] * $result["rate"]) + (($result["quantity"] * $result2["batteryPrice"])* $partPrice));
			break;
		case "brakes":
			$price = (($result["hours"] * $result["rate"]) + (($result["quantity"] * $result2["brakesPrice"])* $partPrice));
			break;
		case "wiperblades":
			$price = (($result["hours"] * $result["rate"]) + (($result["quantity"] * $result2["wiperbladesPrice"])* $partPrice));
			break;
		case "bulbs":
			$price = (($result["hours"] * $result["rate"]) + (($result["quantity"] * $result2["bulbsPrice"])* $partPrice));
			break;
	}
	$quantity = $result["quantity"];
	$summary .= $result["summary"];
}
if(isset($_POST['updateBtn'])){
	$userID = $_POST["username"];
	$price = $_POST["price"];
	$summary = $_POST["summary"];
	$query="SELECT service, serviceDate, serviceTime, location FROM userservice WHERE customerID = '$userID'";
	$result = $link->query($query);
	$result = mysqli_fetch_assoc($result);
	$service = $result["service"];
	$serviceDate = $result["serviceDate"];
	$serviceTime = $result["serviceTime"];
	$location = $result["location"];
	$sql = "INSERT INTO userprevioushistory (userID, service, serviceDate, serviceTime, location, price, summary) VALUES ('$userID', '$service', '$serviceDate', '$serviceTime', '$location', '$price', '$summary')";
	if (mysqli_query($link, $sql)) {
		echo "Invoice successfully created.";
		$sql = "DELETE FROM userservice WHERE customerID = '$userID'";
		if (mysqli_query($link, $sql)) {
			$sql = "DELETE FROM vehiclepart WHERE partID = '$userID'";
			if (mysqli_query($link, $sql)) {
			} else {
				echo "Error: " . $link->error;
			}
		} else {
			echo "Error: " . $link->error;
		}
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
        		<a class="nav-item nav-link" href="service.php">Booking</a>
        		<a class="nav-item nav-link" href="admin.php">Admin</a>
        		<a class="nav-item nav-link" href="report.php">Report</a>
        		<a class="nav-item nav-link" href="parts.php">Parts</a>
        		<a class="nav-item active nav-link" href="invoice.php">Invoice</a>
        	</div>
		</div>
	</nav>
	<div class="container-fluid min-vh-100">
		<div class="row imageContainer">
			<img class="header" src="Images/mechanic.jfif">
			<div class="centered headerText display-2">Invoice Page</div>
		</div>
		<p class="headerText display-4">Invoice</p>
		<form action="" method="POST">
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text">Username</span>
				</div>
				<select class="form-control form-control-lg" name="username" value="<?php echo $username ?>"></select>
			</div>
			<button type="submit" name="submitBtn" class="btn btn-primary btn-lg mb-1">Submit</button>
			<div class="row justify-content-center">
				<div class="form-group col-sm">
					<label for="progress">Part fitted</label>
					<input class="form-control form-control-lg" value="<?php echo $part ?>" readonly>
				</div>
				<div class="form-group col-sm">
					<label for="progress">Quantity</label>
					<input class="form-control form-control-lg" value="<?php echo $quantity ?>" readonly>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="form-group col-sm">
					<label for="progress">Price</label>
					<input class="form-control form-control-lg" name="price" value="<?php echo $price ?>" readonly>
				</div>
				<div class="form-group col-sm">
					<label for="progress">Service Date</label>
					<input class="form-control form-control-lg" value="<?php echo $dateResultText ?>" readonly>
				</div>
			</div>
			<div class="row justify-content-center">
				<div class="form-group col-sm">
					<label for="progress">Summary</label>
					<input class="form-control form-control-lg" name="summary" value="<?php echo $summary ?>" readonly>
				</div>
			</div>
			<button type="submit" name="updateBtn" class="btn btn-primary btn-lg mb-1">Finalise</button>
		</form>
	</div>
</body>