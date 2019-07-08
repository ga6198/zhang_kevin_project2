<?php
//work around CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';

// if user clicks on sign up button
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

//validation done in register.html

//checking email
if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
	echo "invalid email";
	exit();
}

// get user based on email
$nameQuery = "SELECT * FROM users WHERE username = ? LIMIT 1";

$stmt = $conn->prepare($nameQuery);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$userCount = $result->num_rows;
$stmt->close();

if ($userCount > 0)
{
	echo "username exists";
}
else
{
	//security for password
	$password = password_hash($password, PASSWORD_DEFAULT);
	$token = bin2hex(random_bytes(50));
	$verified = false;

	$sql = "INSERT INTO users (username, email, verified, token, password, profile_picture) VALUES (?, ?, ?, ?, ?, 'vanguard_blue.jpg')";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('ssiss', $username, $email, intval($verified), $token, $password);
	if ($stmt->execute())
	{
		// user is registered

		// send verification email
		//sendVerificationEmail($email, $token);
		
		
		echo "registration success";
	}
	else
	{
		echo "registration failed";
	}
	$stmt->close();
}

?>