<!-- PHP sign in code from https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php -->
<?php
require_once "config.php";
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
	// Close connection
	mysqli_close($link);
}
?>
<!DOCTYPE html>
<body>
<head>
<meta charset="UTF-8">
<title>Sign Up</title>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" 
integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" 
integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<link rel="stylesheet" type="text/css" href="format.css">
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
        		<a class="nav-item nav-link" href="service.php">Booking</a>
        	</div>
        	<a href="register.php"><button class="btn btn-primary mr-1">Sign Up</button></a>
        	<a href="login.php"><button class="btn btn-primary mr-1">Login</button></a>
		</div>
	</nav>
	<div class="row imageContainer">
			<img class="header" src="Images/mechanic.jfif">
			<div class="centered headerText display-2">Create an account</div>
	</div>
    <div class="container-fluid">
    	<div class="row">
	        <form class="col-sm border bg-light" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
	                <label>Username</label>
	                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
	                <span class="help-block"><?php echo $username_err; ?></span>
	            </div>    
	            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
	                <label>Password</label>
	                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
	                <span class="help-block"><?php echo $password_err; ?></span>
	            </div>
	            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
	                <label>Confirm Password</label>
	                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
	                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            	</div>
	            <div class="form-group">
	                <input type="submit" class="btn btn-primary" value="Submit">
	                <input type="reset" class="btn btn-default border bg-light" value="Reset">
	            </div>
	            <p>Already have an account? <a href="login.php">Login here</a>.</p>
	        </form>
	    </div>
    </div> 
</div>
</body>
</html>