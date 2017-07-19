<?php

/**
 *  Run a server on TCP/IP
 *  @author Etienne
 *  @todo: define & implement protocol
 *  @todo implement handshake, paquet parser & request manager
 */
class SocketServer
{
    const HOST = '0.0.0.0';
    const PORT = '5000';
    const MAX_CLIENT = 10;

    /**
     * Master Socket, used to detect new connections
     */
    private $socket;

    /**
     * array of clients
     */
    private $clients;

    public function init()
    {
        // reduce errors output & set max exec time of script to unlimited
        error_reporting(~E_WARNING);
        set_time_limit (0);

        // Create TCP IP socket
        echo 'Creating socket ...' . "\r\n";
        $socket = socket_create(AF_INET, SOCK_STREAM, 0);
        if(!$socket) {
            throw new Exception(socket_last_error($this->socket));
            echo 'Could not start server, exiting ...' . " \r\n";
            exit;
        } else {
            $this->socket = $socket;
            echo 'OK!' . "\r\n";
        }

        // Bind socket to the port
        echo 'Binding socket ...' . "\r\n";
        if(!socket_bind($this->socket, self::HOST, self::PORT)) {
            throw new Exception(socket_last_error($this->socket));
            echo 'Could not bind socket, exiting' . "\r\n";
            exit;
        } else {
            echo 'OK!' . "\r\n";
        }
        // set array of clients connections
        $this->clients = array();
    }

    public function listen()
    {
        // set socket to listen, max queued request = 10
        echo 'Listening ...';
        if(!socket_listen($this->socket, 10)) {
            throw new Exception(socket_last_error($this->socket));
            echo 'Socket could not be set to listening' . "\r\n";
            exit;
        }
        echo 'Server is listening !' . "\r\n";
        echo 'Waiting for connections' . "\r\n";

        while(true) {
            // set array of readable client socket
            $read = array();
            // add master socket as first socket in array
            $read[] = $this->socket;
            // add every connected clients
            foreach($this->clients as $client) {
                $read[] = $client;
            }
            // call select, non blocking, fire when change of state is observed on read, write or error
            if(socket_select($read, $write, $except, null) === false) {
                throw new Exception(socket_last_error($this->socket));
            }

            $this->checkForNewConnection($read);

            $this->sendUserMessages($read);

            // //check each client for new sent data
            // foreach($this->clients as $client) {
            //     if (in_array($client, $read)) {
            //         $clientMessage = socket_read($client, 1024);
            //
            //         //zero length string meaning disconnected, remove and close the socket
            //         if ($clientMessage == null) {
            //             echo 'user logging off' . "\r\n";
            //             socket_close($client);
            //             unset($client);
            //             continue;
            //         }
            //
            //         $outputMessage = trim($clientMessage);
            //         $outputMessage . " \n";
            //         echo "Sending output to client \n";
            //         echo $outputMessage . "\n";
            //
            //         //send response to clients
            //         foreach($this->clients as $chatClient) {
            //             if($client != $chatClient) {
            //                 socket_write($chatClient , $outputMessage);
            //             }
            //         }
            //     }
            // }
        }
    }

    /**
     * Check if new incoming connection, this is done by checking
     * if $read contains the master socket.
     */
    private function checkForNewConnection($readableSockets)
    {
        if(in_array($this->socket, $readableSockets)) {
            $newClient = socket_accept($this->socket);
            // if server accepts more connections
            if(count($this->clients < self::MAX_CLIENT)) {
                $this->clients[] = $newClient;
                // Log client informations in terminal
                $time = date('d-m-y H:m:s');
                if(socket_getpeername($newClient, $address, $port)) {
                    echo $time . " Client $address : $port has joined the session. \n";
                } else {
                    echo $time . " Unknown client has joined the session. \n";
                }
                unset($time);
                //Send Welcome message to client
                $message = "Welcome to ShellChat \n";
                socket_write($newClient, $message);
                unset($newClient);
            } else {
                // send sorry message and close connection
                $message = "Max connections reached, please try again later \n";
                socket_write($newClient, $message);
                socket_close($newClient);
            }
        }
    }

    private function sendUserMessages($readableSockets)
    {
        //check each client for new sent data
        foreach($this->clients as $client) {
            if (in_array($client, $readableSockets)) {
                $clientMessage = socket_read($client, 1024);

                //zero length string meaning disconnected, remove and close the socket
                if ($clientMessage == null) {
                    echo 'user logging off' . "\r\n";
                    socket_close($client);
                    unset($client);
                    continue;
                }

                $outputMessage = trim($clientMessage);
                $outputMessage . " \n";
                echo "Sending output to client \n";
                echo $outputMessage . "\n";

                //send response to clients
                foreach($this->clients as $chatClient) {
                    if($client != $chatClient) {
                        socket_write($chatClient , $outputMessage);
                    }
                }
            }
        }
    }

    public function shutdown()
    {
        echo 'Closing socket & exiting...' . "\r\n";
        socket_close($this->socket);
        exit;
    }

}
