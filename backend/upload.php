<?php
//this script is handling hte upload of the document that the admin wants to send as an attachment to the server

header('Access-Control-Allow-Origin: *');  

if (move_uploaded_file($_FILES["file"]["tmp_name"], "upload/".$_FILES['file']['name'])) {
    echo "OK";
    exit;
}

echo "FAIL";

?>
