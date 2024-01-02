<html>
<link href="/CarRental/Theme/pstyles.css" rel="stylesheet" type="text/css" />
<body>
    <?php
    $database_host = "localhost";
    $database_user = "root";
    $database_pass = "";
    $database_name = "carrental";

    $connection = mysqli_connect($database_host, $database_user, $database_pass, $database_name);
    if (mysqli_connect_errno()) {
        die("Failed connecting to MySQL database. Invalid credentials" . mysqli_connect_error() . "(" . mysqli_connect_errno() . ")");
    }

    $office_id = $_POST["office_id"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $country = $_POST["country"];
    $city = $_POST["city"];

    // Check if the office_id exists
    $checkOfficeIdSql = "SELECT * FROM office WHERE office_id = '$office_id'";
    $checkOfficeIdResult = $connection->query($checkOfficeIdSql);
    // Check if the office_id exists
    $checkphoneSql = "SELECT * FROM office WHERE phone = '$phone'";
    $checkphoneResult = $connection->query($checkphoneSql);
    // Check if the office_id exists
    $checkemailSql = "SELECT * FROM office WHERE email = '$email'";
    $checkemailResult = $connection->query($checkemailSql);

	if ($checkOfficeIdResult->fetch_assoc()){
		// Office ID already exists
        echo '<script>alert("Office ID already exists!");</script>';
	}
    elseif ($checkphoneResult->fetch_assoc()){
		// Phone already exists
        echo '<script>alert("Phone already exists!");</script>';
	}
    elseif ($checkemailResult->fetch_assoc()){
		// Email already exists
        echo '<script>alert("Email already exists!");</script>';
	}
    else {
        $sql = "INSERT INTO office(office_id, phone, email, country, city) VALUES ('$office_id','$phone','$email','$country','$city')";
        $connection->query($sql);
        echo '<script>alert("New office has been successfully added");</script>';
    }
    
	echo "<script> window.location.href='/CarRental/Admin/Frontend/office.html'; </script>";
?>
</body>
</html>
