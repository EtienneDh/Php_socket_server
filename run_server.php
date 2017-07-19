<?php
require_once('Classes/SocketServer.php');

$server = new SocketServer();

// Initialize server
echo 'Initializing server ...' . "\r\n";
try {
    $server->init();
} catch(Exception $e) {
    echo $e;
}

$server->run();
