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

// if user clicks on login button
$username = $_POST['username'];
$password = $_POST['password'];

//validation is done in javascript index.html

$sql = "SELECT * FROM users WHERE email=? OR username=? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ss', $username, $username);
$stmt->execute();
// checking if user exists
$result = $stmt->get_result();
$resultCount = $result->num_rows; 
$user = $result->fetch_assoc();

if (password_verify($password, $user['password']))
{
	//login success
	/*$_SESSION['id'] = $user['id'];
	$_SESSION['username'] = $user['username'];
	$_SESSION['email'] = $user['email'];
	$_SESSION['verified'] = $user['verified'];*/
	
	//echo $_SESSION['message'];
	echo "logged in";

}

?>