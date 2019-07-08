<?php
/*
// Set new file name
$new_image_name = "newimage_".mt_rand().".jpg";

//store image name in database


// upload file
move_uploaded_file($_FILES["file"]["tmp_name"], 'profileImages/'.$new_image_name);
echo $new_image_name ;
*/
$user_id = $_POST['user_id'];

$imgdata = $_POST['img_data'];

//header("Content-type: image/jpg"); //use this if you need to echo an image
$image = base64_decode($imgdata);

// get image type
$f = finfo_open();
$mime_type = finfo_buffer($f, $image, FILEINFO_MIME_TYPE);
//echo $mime_type;

//$file_type = end(split('/',$mime_type)); //get last part of mime type. e.g. jpg, png
$file_type = end(preg_split('/\//',$mime_type)); //get last part of mime type by splitting on /. e.g. jpg, png
//echo $file_type;

//save image to profile picture folder
$file_name = $user_id . "_profileImage." . $file_type;
$full_file_dir = "profileImages/" . $file_name;

file_put_contents($full_file_dir, $image);

//store image name in database


//echo $image;
//echo '<img src=" . $image . " />';
?>