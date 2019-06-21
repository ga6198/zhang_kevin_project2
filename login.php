<?php
/*spl_autoload_register( 'autoload' );

function autoload( $class, $dir = null ) {

	if ( is_null( $dir ) )
		//$dir = '/path/to/project';
		$dir = dirname(__FILE__)."/../";
		//echo $dir;

	foreach ( scandir( $dir ) as $file ) {

	  // directory?
	  if ( is_dir( $dir.$file ) && substr( $file, 0, 1 ) !== '.' )
		autoload( $class, $dir.$file.'/' );

	  // php file?
	  if ( substr( $file, 0, 2 ) !== '._' && preg_match( "/.php$/i" , $file ) ) {

		// filename matches class?
		if ( str_replace( '.php', '', $file ) == $class || str_replace( '.class.php', '', $file ) == $class ) {

			include $dir . $file;
		}
	  }
	}
}*/

// need to include all classes before session_start()
//require_once dirname(__FILE__)."/../card.class.php";
//require_once dirname(__FILE__)."/../deck.class.php";

//cors header

/*
// * wont work in FF w/ Allow-Credentials
//if you dont need Allow-Credentials, * seems to work
header('Access-Control-Allow-Origin: http://127.0.0.1/zhang_kevin_project2/login.php');
//if you need cookies or login etc
header('Access-Control-Allow-Credentials: true');
if ($this->getRequestMethod() == 'OPTIONS')
{
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
  header('Access-Control-Max-Age: 604800');
  //if you need special headers
  header('Access-Control-Allow-Headers: x-requested-with');
  exit(0);
}
*/

//header('Access-Control-Allow-Origin: *');

//header('Access-Control-Allow-Origin: 127.0.0.1');
//header('Access-Control-Allow-Credentials: true');
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
//$password = "";
//$password = "";

// if user clicks on login button
$username = $_POST['username'];
$password = $_POST['password'];

//validation
if(empty($username))
{
	$errors['username'] = "Username required";
}
if(empty($password))
{
	$errors['password'] = "Password required";
}
	
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
	
	/*if ($resultCount <= 0){
		echo 'user not found';
	}
	else{*/
	if (password_verify($password, $user['password']))
	{
		//login success
		// login user using session
		$_SESSION['id'] = $user['id'];
		$_SESSION['username'] = $user['username'];
		$_SESSION['email'] = $user['email'];
		$_SESSION['verified'] = $user['verified'];
		// set flash message
		$_SESSION['message'] = "You are now logged in!";
		$_SESSION['alert-class'] = "alert-success";
		
		echo $_SESSION['message'];
		
		//header('location: index.php'); //redirect
		//exit(); // exit this page and don't execute anything else on it
		
		/*data = array();
		
		while ($row = mysqli_fetch_object($result)){
			$data[]=$row;
		}
		echo json_encode($data);
		*/
	}
	else
	{
		$errors['login_fail'] = "Wrong credentials";
		echo "failed";
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