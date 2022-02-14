<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Max-Age: 3628800');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');    

/*
sending mail is possible thanks to the Swiftmailer library
you will need to install Swiftmailer wiht composer to being able to use this php page
after you install the library you will have to import autoload.php to this page
*/

if($_SERVER['REQUEST_METHOD'] == "POST" and !empty($_POST)){
   send();
}//if-else

function send(){
   
   error_reporting(E_ALL);
   ini_set('display_errors', '1');
   

   //acess to you db containing the user info
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



	// create an transport object
	$transport = (new Swift_SmtpTransport('yourdomainsmtpaddress', #ofportneeded, 'typeofencryption'))
	  ->setUsername('youemailaddress@domain.aaa')
	  ->setPassword('youemailaddresspasswordcasesentitive');
	;	
	
	// initialize a mailer transport
	$mailer = new Swift_Mailer($transport);
	
	// create the message
	$message = new Swift_Message();
	
	// initialize the message headers
	$headers = $message->getHeaders();
	
   
	//set the object of the mail with the one written by the admin
	$message->setSubject($_POST['object']);
	//set the addredd which is sendind the mail
	$message->setFrom(['senderaddress@domain.aaa' => 'nameofthesender']);
	
   echo "sending...<br>";
	
	//initialize an antiflood, stop the script for 60 seconds after sending 30 message and the restart
	$mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(30, 60));
	//set how many messages you can send in a minute
	$mailer->registerPlugin(new Swift_Plugins_ThrottlerPlugin(60, Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE));
	
	//set the mail text with the markup text written by the admin
	$testo_newsletter = $_POST['input']; 
 	
   //enter the db to fetch the user info, in this case the db was a MySQL db
   if (mysqli_num_rows($result) > 0) {
   
      while($rowData = mysqli_fetch_array($result)){
           
           $message->setTo([$rowData["email"] => $rowData["name"]]);

           $message->setBody(
              '<html>
                 <head>
                  <title>Prova invio mail</title>
                 </head>
                 <body>' .$testo_newsletter .'
                 </body>
               </html>',
                'text/html', //set the mail text as an HTML page
                'utf-8'	//char codification	 
           );
           //if there are attachment add those to the mail
           if($_POST['attachment']!==""){
               $message->attach(Swift_Attachment::fromPath('upload/'.$_POST['attachment']));
            }
           $result = $mailer->send($message);
           
           
           
           if($result==0){
                
              echo "Email sent<br>";
              
           }else{
              
              echo "Email not sent <br>";
             
           }//if-else
      }
      
   }

}//send

?>
