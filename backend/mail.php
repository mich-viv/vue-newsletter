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



	// crea un oggetto della classe Transport
	$transport = (new Swift_SmtpTransport('smtp.libero.it', 465, 'ssl'))
	  ->setUsername('mikiviv@libero.it')
	  //->setPassword('55lv1,618033LS21')
	              //vecchia password?'21l50V1,61803'
		->setPassword('51121292123');
	;	
	
	// inizializza un mailer che spedirà il messaggio mediante il trasporto
	$mailer = new Swift_Mailer($transport);
	
	// crea il messaggio
	$message = new Swift_Message();
	
	//inizializza gli headers per 
	$headers = $message->getHeaders();
	
   
	//imposta l'oggetto della mail
	$message->setSubject($_POST['object']);
	//imposta chi spedisce la mail
	$message->setFrom(['mikiviv@libero.it' => 'Michele Viviani']);//impostare indirizzo mail del dominio
	
   echo "invio in corso...<br>";
	
	//imposta un limite antiflood, pausa l'invio dopo 30 mail e attendi 60 secondi prima di continuare l'invio
	$mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(30, 60));
	//imposta un limite al throttle, rateo massimo di invio dei messaggi 60 al minuto
	$mailer->registerPlugin(new Swift_Plugins_ThrottlerPlugin(60, Swift_Plugins_ThrottlerPlugin::MESSAGES_PER_MINUTE));
	
	//crea le variabili che comprendono i vari elementi dell'anteprima
	$testo_newsletter = $_POST['input']; 

   if (mysqli_num_rows($result) > 0) {
   
      while($rowData = mysqli_fetch_array($result)){
           //echo $rowData["name"].$rowData["email"];
           $message->setTo([$rowData["email"] => $rowData["name"]]);

           $message->setBody(
              '<html>
                 <head>
                  <title>Prova invio mail</title>
                 </head>
                 <body>' .$testo_newsletter .'
                 </body>
               </html>',
                'text/html', //imposta la mail come pagina html
                'utf-8'	//utilizza questa codifica di caratteri	 
           );
           
           if($_POST['attachment']!==""){
               $message->attach(Swift_Attachment::fromPath('http://localhost/test/vue-newsletter/upload/'.$_POST['attachment']));
            }
           $result = $mailer->send($message);
           
           
           
           if($result==0){
                
              echo "invio non riuscito <br>";
              
           }else{
              
              echo "invio effettuato <br>";
             
           }//if-else
      }
      
   }

}//send

?>