<?php

//this script will create a list of all the attachmente already present in the server 

header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 3628800');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');  

$fileList = glob('upload/*');

foreach($fileList as $filename){
	if(is_file($filename)){
		echo str_replace("upload/","",$filename)."~~~";
	}
}//foreach
?>
