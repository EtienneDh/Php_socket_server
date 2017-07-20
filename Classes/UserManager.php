<?php

require_once('Classes/User.php');

class UserManager
{
    /**
     * array $userCollection Array of logged users' ip & port
     */
    private $userCollection = array();

    /**
     * array $pseudoAddressTable Array: [pseudo] => "ip/port"
     */
    private $pseudoAddressTable = array();

    /**
     * Creates a user instance & adds it to userCollection
     */
    public function createUser($ip, $port)
    {
        $user = new User($ip, $port);
        $this->addUser($user);
    }

    public function getCurrentUserPseudo($ip, $port)
    {
        if(count($this->pseudoAddressTable) > 0) {
            foreach($this->pseudoAddressTable as $pseudo => $address) {
                if((string) $ip . "/" . (string) $port == $address) {
                    return $pseudo;
                }
            }
        }
        return 'Unknown';
    }

    /**
     * Adds user to userCollection
     */
    private function addUser($user)
    {
        $this->userCollection[] = $user;
        // echo "added user \n";
        var_dump($this->userCollection);
    }

    /**
     * Registers user to $pseudoAddressTable
     */
    private function registerUser($pseudo, $address, $port)
    {
        if(!is_null($address) && !is_null($port)) {
            $this->pseudoAddressTable[$pseudo] = (string) $address . "/" . (string) $port;
            echo "registered user \n";
        }
    }

    /**
     * The main purpose of this method is to bind a pseudo on an ip.
     * Also increments user sent messages
     */
    public function updateUser($clientMessage, $address, $port)
    {
        echo "updating user \n";
        $user = $this->getUserByIpAndPort($address, $port);
        if(null !== $user) {
            echo $user->getPseudo() . "\n";
            if($user->getPseudo() == '' || null === $user->getPseudo()) {
                echo "setting new pseudo \n";
                $user->setPseudo($clientMessage);
                echo " pseudo: " . $user->getPseudo() . "\n";
                $this->registerUser($clientMessage, $address, $port);
            }
            $user->incrementMsgSent();
        }
        else {
            echo "user null \n";
        }
    }

    public function getUserCount()
    {
        return count($this->pseudoAddressTable);
    }

    private function getUserByIpAndPort($ip, $port)
    {
        foreach($this->userCollection as $user) {
            if($user->getIp() == $ip && $user->getPort() == $port) {
                return $user;
            }
        }
        return null;
    }
}
