<?php

/**
 *  Run a server on UDP protocol to easily deal with multiples connections
 *  @author Etienne
 *  @todo: define & implement protocol
 *  @todo implement handshake, paquet parser & request manager
 */
class SocketServer
{
    const HOST = '127.0.0.1';
    const PORT = '8080';

    private $socket;

    public function init()
    {
        error_reporting(~E_WARNING);

        // Create UPD socket
        echo 'Creating socket ...' . "\r\n";
        $socket = socket_create(AF_INET, SOCK_DGRAM, 0);
        if(!$socket) {
            throw new Exception(socket_last_error($this->socket));
        } else {
            $this->socket = $socket;
            echo 'OK!' . "\r\n";
        }

        // Bind socket to the port
        echo 'Binding socket ...' . "\r\n";
        if(!socket_bind($this->socket, self::HOST, self::PORT)) {
            throw new Exception(socket_last_error($this->socket));
        } else {
            echo 'OK!' . "\r\n";
        }
    }

    public function listen()
    {
        while(true) {
            echo 'Waiting for data ...' . "\r\n";

            //Receive some data
            $r = socket_recvfrom($this->socket, $buf, 512, 0, $remote_ip, $remote_port);
            echo "$remote_ip : $remote_port -- " . $buf;

            //Send back the data to the client
            socket_sendto($this->socket, "OK " . $buf , 100 , 0 , $remote_ip , $remote_port);
        }

    }

    public function shutdown()
    {
        echo 'Closing socket & exiting...' . "\r\n";
        socket_close($this->socket);
        exit;
    }


    public function getSocket()
    {
        return $this->socket;
    }
}
