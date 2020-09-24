<?php

$con=mysqli_connect("localhost","root","","application_db");
$con -> set_charset("utf8");

if (mysqli_connect_error())
 {
	echo "Fail to connect with Data base".mysqli_connect_errno();
 }



  ?>