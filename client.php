<?php

// $host = '51.254.140.189';
$host = '127.0.0.1';
$port = '5000';

echo 'Welcome to ShellChat !' . "\r\n";
echo 'Enter your message or type :q to quit' . "\r\n";

//Creation de la socket
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('Création de socket refusée');
//Connexion au serveur
socket_connect($sock,$host,$port) or die('Connexion impossible');

//Ecriture du paquet vers le serveur
socket_write($sock,$paquet,$write_len);

//Fermeture de la connexion
// socket_close($sock);

while(true) {
    // read incoming msg
    $input = socket_read($sock, 1024);
    if(null != $input && $input != '') {
        echo $input . "\r\n";
    }

    $userMsg = fgets(STDIN);

    //Send the message to the server
    if( ! socket_sendto($sock, $userMsg , strlen($userMsg) , 0 , $host , $port))
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
    echo $reply;

}

 ?>
