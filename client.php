<?php

// $host = '51.254.140.189';
$host = '127.0.0.1';
$port = '8080';

echo 'Welcome to ShellChat !' . "\r\n";
echo 'Enter your message or type :q to quit' . "\r\n";


if(!($sock = socket_create(AF_INET, SOCK_DGRAM, 0)))
{
	$errorcode = socket_last_error();
    $errormsg = socket_strerror($errorcode);

    die("Couldn't create socket: [$errorcode] $errormsg \n");
}

do {
    $handle = fopen ("php://stdin","r");
    $input = fgets($handle);

    if(trim($input) === ':q') {
        echo 'Goodbye' . "\r\n";
        socket_close($sock);
        exit;
    }

    //Send the message to the server
	if(!socket_sendto($sock, $input , strlen($input) , 0 , $host , $port))
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);

		die("Could not send data: [$errorcode] $errormsg \n");
	}

	//Now receive reply from server and print it
	if(socket_recv ( $sock , $reply , 2045 , MSG_WAITALL ) === FALSE)
	{
		$errorcode = socket_last_error();
		$errormsg = socket_strerror($errorcode);

		die("Could not receive data: [$errorcode] $errormsg \n");
	}

	echo "Reply : $reply";

} while(true);

echo 'closing connection...';
socket_close($sock);
echo ' Ok, exiting';
exit;
//
//
//
//
// function sendMessage($sock, $input)
// {
//     //Send the message to the server
// 	if( ! socket_sendto($sock, $input , strlen($input) , 0 , $server , $port))
// 	{
// 		$errorcode = socket_last_error();
// 		$errormsg = socket_strerror($errorcode);
//
// 		die("Could not send data: [$errorcode] $errormsg \n");
// 	}
//
// 	//Now receive reply from server and print it
// 	if(socket_recv ( $sock , $reply , 2045 , MSG_WAITALL ) === FALSE)
// 	{
// 		$errorcode = socket_last_error();
// 		$errormsg = socket_strerror($errorcode);
//
// 		die("Could not receive data: [$errorcode] $errormsg \n");
// 	}
//
// 	echo "Reply : $reply";
// }





// $message= 'hello server my old friend';
// //Creation de la socket
//  $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('Création de socket refusée');
// //Connexion au serveur
//  socket_connect($sock,$host,$port) or die('Connexion impossible');
// //Codage de la longueur du Pseudo
//
//
//
// //Construction du paquet à envoyer au serveur
//  $paquet=$message;
// //Calcul de la longueur du paquet
//  $write_len=strlen($message);
// //Ecriture du paquet vers le serveur
//  socket_write($sock,$paquet,$write_len);
// //Fermeture de la connexion
//  socket_close($sock);
//
//

 ?>
