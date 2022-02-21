<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 3628800');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');    
require('C:\xampp\htdocs\test\vendor\autoload.php');

$name = "Undefined name";



// Allow from any origin
/*
     if(isset($_SERVER["HTTP_ORIGIN"]))
     {
        // You can decide if the origin in $_SERVER['HTTP_ORIGIN'] is something you want to  allow, or as we do here, just allow all
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
     }
     else
     {
        //No HTTP_ORIGIN set, so we allow any. You can disallow if needed here
        header("Access-Control-Allow-Origin: *");
     }

     header("Access-Control-Allow-Credentials: true");
     header("Access-Control-Max-Age: 600");    // cache for 10 minutes

     if($_SERVER["REQUEST_METHOD"] == "OPTIONS")
     {
        if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_METHOD"]))
            header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT"); 
  //Make  sure you remove those you do not want to support

        if (isset($_SERVER["HTTP_ACCESS_CONTROL_REQUEST_HEADERS"]))
            header("Access-Control-Allow-Headers: 
           {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

           //Just exit with 200 OK with the above headers for OPTIONS method
        exit(0);
      }*/
      //print_r($_POST);
      /*
      foreach($_POST as $key=>$value)
{
  echo "$key";
}*/      


if($_SERVER['REQUEST_METHOD'] == "POST" and !empty($_POST)){
   send();
}//if-else

function send(){
   
   error_reporting(E_ALL);
   ini_set('display_errors', '1');
   

   //accesso al database
   $host = "localhost"; 
   $user = "root"; 
   $password = ""; 
   $dbname = "newsletterdb"; 
   $id = '';

   $con = mysqli_connect($host, $user, $password,$dbname);

   if (!$con) {
      die("Connection failed: " . mysqli_connect_error());
   }
   $sql = "select name, email 
           from contacts";


   // run SQL statement
   $result = mysqli_query($con,$sql);

   // die if SQL statement failed
   if (!$result) {
      http_response_code(404);
      die(mysqli_error($con));
   }
	
	//new php mailer instance
	$mail = new PHPMailer(true);
	
	try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'user@example.com';                     //SMTP username
    $mail->Password   = 'secret';                               //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
	
	//Recipients
    $mail->setFrom('yourmailaddress@domain.aaa', 'name of the admin');//sender
	
	if (mysqli_num_rows($result) > 0) {
	
		while($rowData = mysqli_fetch_array($result)){
            
            $mail->addAddress([$rowData["email"] => $rowData["name"]]);

            //Content of the message for each user in the db
		    $mail->isHTML(true);//Set email format to HTML
		    $mail->Subject = $_POST['object'];
		    $mail->Body    = $_POST['input'];
           
            if($_POST['attachment']!==""){
               $mail->addAttachment('http://localhost/test/vue-newsletter/upload/'.$_POST['attachment']);
            }
            $mail->send();
           
           
        }
	
	}


		echo 'Message has been sent';
	} catch (Exception $e) {
		echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
	}
	

?>