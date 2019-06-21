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
require_once dirname(__FILE__)."/../card.class.php";
require_once dirname(__FILE__)."/../deck.class.php";

session_start();

require_once 'config/db.php';
require_once 'emailController.php';

//store error messages
$errors = array();
$username = "";
$email = "";
//$password = "";
//$password = "";

// if user clicks on sign up button
if(isset($_POST["signup-btn"]))
{
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$passwordConfirm = $_POST['passwordConfirm'];
	
	//validation
	if(empty($username))
	{
		$errors['username'] = "Username required";
	}
	
	if(!filter_var($email, FILTER_VALIDATE_EMAIL))
	{
		$errors['email'] = "Email address is invalid";
	}
	
	if(empty($email))
	{
		$errors['email'] = "Email required";
	}
	if(empty($password))
	{
		$errors['password'] = "Password required";
	}
	
	if ($password !== $passwordConfirm)
	{
		$errors['password'] = "The two passwords do not match";
	}
	
	// get user based on email
	$emailQuery = "SELECT * FROM users WHERE email = ? LIMIT 1";
	
	$stmt = $conn->prepare($emailQuery);
	$stmt->bind_param('s', $email);
	$stmt->execute();
	$result = $stmt->get_result();
	$userCount = $result->num_rows;
	$stmt->close();
	
	if ($userCount > 0)
	{
		$errors['email'] = "Email already exists";
	}
	
	if (count($errors) === 0)
	{
		$password = password_hash($password, PASSWORD_DEFAULT);
		$token = bin2hex(random_bytes(50));
		$verified = false;
		
		$sql = "INSERT INTO users (username, email, verified, token, password) VALUES (?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param('ssiss', $username, $email, intval($verified), $token, $password);
		if ($stmt->execute())
		{
			// user is registered
			// login user using session
			$user_id = $conn->insert_id;
			$_SESSION['id'] = $user_id;
			$_SESSION['username'] = $username;
			$_SESSION['email'] = $email;
			$_SESSION['verified'] = $verified;
			
			// send verification email
			sendVerificationEmail($email, $token);
			
			
			// set flash message
			$_SESSION['message'] = "You are now logged in!";
			$_SESSION['alert-class'] = "alert-success";
			header('location: index.php'); //redirect
			exit(); // exit this page and don't execute anything else on it
		}
		else
		{
			$errors['db_error'] = "Database error: failed to register";
		}
		$stmt->close();
	}
	
}

// if user clicks on login button
if(isset($_POST["login-btn"]))
{
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
		$user = $result->fetch_assoc();
		
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
		}
		else
		{
			$errors['login_fail'] = "Wrong credentials";
		}
	}
}


// logout user
if (isset($_GET['logout']))
{
	//session_unset();
	
	session_destroy();
	
	
	/*unset($_SESSION['id']);
	unset($_SESSION['username']);
	unset($_SESSION['password']);
	unset($_SESSION['verified']);*/
	
	// unset all session variables
	$helper = array_keys($_SESSION);
    foreach ($helper as $key){
        unset($_SESSION[$key]);
    }
	
	
	header('location: login.php');
	exit();
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