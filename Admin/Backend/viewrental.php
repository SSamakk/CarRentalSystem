<html>
<link href="/CarRental/Theme/pstyles.css" rel="stylesheet" type="text/css" />	
<body>
<?php
	$database_host = "localhost";
	$database_user = "root";
	$database_pass = "";
	$database_name = "carrental";
	$connection = mysqli_connect($database_host, $database_user, $database_pass, $database_name);
	if(mysqli_connect_errno()){
		die("Failed connecting to MySQL database. Invalid credentials" . mysqli_connect_error(). "(" .mysqli_connect_errno(). ")" ); 
	}
	
?>
<?php
$res2="SELECT * FROM reservation";
	$result2=mysqli_query($connection,$res2);
	echo "<h1><center>Reservations Info</h1><br><br>";
?>
<center>
<table border='1'>
<tr><br>
<th> &nbsp; &nbsp; Reservation ID &nbsp; &nbsp; </th>
<th> &nbsp; &nbsp; Car ID &nbsp; &nbsp; </th>
<th> &nbsp; &nbsp; Customer ID &nbsp; &nbsp; </th>
<th> &nbsp; &nbsp; Reserve Date &nbsp; &nbsp; </th>
<th> &nbsp; &nbsp; Pickup Date &nbsp; &nbsp; </th>
<th> &nbsp; &nbsp; Return Date &nbsp; &nbsp; </th>
</tr>
<?php
if (mysqli_num_rows($result2) > 0) {
while($row2 = mysqli_fetch_assoc($result2))
{
echo "<tr>";
echo "<td>" . $row2["reservation_id"] . "</td>";
echo "<td>" . $row2["car_id"] . "</td>";
echo "<td>" . $row2["user_id"] . "</td>";
echo "<td>" . $row2["reserve_date"] . "</td>";
echo "<td>" . $row2["pickup_date"] . "</td>";
echo "<td>" . $row2["return_date"] . "</td>";
echo "</tr>";
}
}
?>
</table>
<!-- Add a back button -->
<a href="/CarRental/Admin/Frontend/return.html">Back to Main Page</a>