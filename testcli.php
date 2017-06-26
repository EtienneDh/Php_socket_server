<?php

// $host = '51.254.140.189';
$host = '127.0.0.1';
$port = '5000';

echo 'Welcome to ShellChat !' . "\r\n";
echo 'Enter your message or type :q to quit' . "\r\n";

//Creation de la socket
// $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die('Création de socket refusée');
//Connexion au serveur
// socket_connect($socket, $host, $port) or die('Connexion impossible');

$socket = fsockopen($host, $port, $errno) or die('fail opening socket');


$inputStream = fopen('php://stdin', 'r');
while(true) {
    $read = array($inputStream, $socket);
    if(stream_select($read, $write, $exept, 0) > 0) {
        foreach($read as $input => $source) {
            if($source == $inputStream) {
                // exit('user input');
                fwrite($socket, fgets($inputStream));
            } else {
                // exit('server');
                echo fgets($socket);
            }
        }
    }
}




// //Ecriture du paquet vers le serveur
// $i = 0;
// while ($i < 5) {
//     sleep(2);
//     $msg = ++$i;
//     socket_write($socket, $msg, strlen($msg));
// }
// socket_close($socket);
// exit;
