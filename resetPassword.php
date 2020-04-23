<!-- PHP sign in code from https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php -->
<?php
// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
// Include config file
require_once "config.php";
// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter the new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have atleast 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm the password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE useraccount SET password = ? WHERE id = ?";
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
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
    font-size: calc(12px + 6vmin);
}

.smallText {
    font-size:calc(12px + 3vmin);
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
                <a class="nav-item nav-link" href="userPage.html">Details</a>
                <a class="nav-item nav-link" href="service.html">Booking</a>
            </div>
            <a href="logout.php"><button class="btn btn-primary mr-1">Logout</button></a>
            <a href="resetPassword.php"><button class="btn btn-primary mr-1">Reset Password</button></a>
        </div>
    </nav>
    <div class="row imageContainer">
            <img class="header" src="Images/mechanic.jfif">
            <div class="centered headerText display-2">Reset Password</div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <form class="col-sm border bg-light" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                    <label class="smallText">New Password</label>
                    <input type="password" name="new_password" class="form-control" value="<?php echo $new_password_err; ?>">
                    <span class="help-block"><?php echo $new_password_err; ?></span>
                </div>    
                <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                    <label class="smallText">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a class="btn btn-link" onclick="window.history.back()">Cancel</a>
                </div>
            </form>
        </div>
    </div> 
</div>
</body>
</html>
 