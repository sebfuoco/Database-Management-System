<?php
$username = $_SESSION['username'];
$query = "SELECT id FROM useraccount WHERE username = '$username'";
$userID = $link->query($query);
$row = mysqli_fetch_assoc($userID);
$userID = $row["id"];
?>