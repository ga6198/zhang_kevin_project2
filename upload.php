<?php
//work around CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: X-Requested-With");

session_start();

require_once 'config/db.php';

$user_id = $_POST['user_id'];

$imgdata = $_POST['img_data'];

//header("Content-type: image/jpg"); //use this if you need to echo an image
$image = base64_decode($imgdata);

// get image type
$f = finfo_open();
$mime_type = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
//echo $mime_type;

//$file_type = end(split('/',$mime_type)); //get last part of mime type. e.g. jpg, png
$file_split = preg_split('/\//',$mime_type); //get last part of mime type by splitting on /. e.g. jpg, png
$file_type = end($file_split);
//echo $file_type;

//save image to profile picture folder
$file_name = $user_id . "_profileImage." . $file_type;
$full_file_dir = "profileImages/" . $file_name;

file_put_contents($full_file_dir, $image);

//store image name in database
$query = "UPDATE users SET profile_picture = ? WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $file_name, $user_id);
$stmt->execute();
$stmt->close();


//echo picture
//$contentHeader = "Content-type: image/" . $file_type;
//header($contentHeader);
//echo $image;
echo $full_file_dir;


//echo $image;
//echo '<img src=" . $image . " />';
?>