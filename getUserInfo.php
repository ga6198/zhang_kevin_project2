<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");


session_start();

require_once 'config/db.php';
require_once 'user.class.php';
//require_once 'emailController.php';

//store error messages
$errors = array();
$username = "";
$email = "";

$username = $_POST['username'];
//$password = $_POST['password'];

//validation
if(empty($username))
{
	$errors['username'] = "Username required";
}
/*if(empty($password))
{
	$errors['password'] = "Password required";
}*/
	
if(count($errors) === 0)
{
	$sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('ss', $username, $username);
	$stmt->execute();
	// checking if user exists
	$result = $stmt->get_result();
	$resultCount = $result->num_rows; //can't get rows of non-object. LIMIT 1 likely caused this
	$user = $result->fetch_assoc();
	
	if ($resultCount <= 0){
		echo 'user not found';
	}
	else{
		//login success
		
		$id = $user['id'];
		$newUsername = $user['username'];
		$email = $user['email'];
		$verified = $user['verified'];
		$token = $user['token'];
		$password = $user['password'];
		
		$newUser = new User($id, $newUsername, $email, $verified, $token, $password);
		
		echo json_encode($newUser);
		
		/*data = array();
		
		while ($row = mysqli_fetch_object($result)){
			$data[]=$row;
		}
		echo json_encode($data);
		*/
	}
}

// verify user by token
function verifyUser($token)
{
	global $conn;
	//$sql = "SELECT * FROM users WHERE token='$token' LIMIT 1";
	//$result = mysqli_query($conn, $sql);
	$sql = "SELECT * FROM users WHERE token=? LIMIT 1";
	
	$stmt = $conn->prepare($sql);
	$stmt->bind_param('s', $token);
	$stmt->execute();	
	$result = $stmt->get_result();
	
	$stmt->close();
	
	// ensuring the user exists
	if(mysqli_num_rows($result) > 0)
	{
		$user = mysqli_fetch_assoc($result); // associative array
		// verify user by setting verified from 0 to 1
		$update_query = "UPDATE users SET verified=1 WHERE token=?";
		
		$stmt = $conn->prepare($update_query);
		$stmt->bind_param('s', $token);
		
		if($stmt->execute())
		{
			//log user in
			//login success
			// login user using session
			$_SESSION['id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['email'] = $user['email'];
			$_SESSION['verified'] = 1;
			// set flash message
			$_SESSION['message'] = "Your email address was successfully verified";
			$_SESSION['alert-class'] = "alert-success";
			header('location: index.php'); //redirect
			exit(); // exit this page and don't execute anything else on it
		}
		else
		{
			echo "User not found";
		}
		
		$stmt->close();
	}
}

?>