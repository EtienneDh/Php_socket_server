<?php

require_once('Classes/User.php');

class UserManager
{
    private $userCollection = array();

    public function createUser($ip, $port)
    {
        $user = new User($ip, $port);
        $this->addUser($user);
    }

    private function addUser($user)
    {
        $this->userCollection[] = $user;
    }

    /**
     * The main purpose of this method is to bind a pseudo on an ip.
     */
    public function updateUser($clientMessage, $address, $port)
    {
        $user = $this->getUserByIpAndPort($address, $port);
        if(0 != $user) {
            $user->setPseudo($clientMessage);
        }
    }

    private function getUserByIpAndPort($ip, $port)
    {
        foreach($this->userCollection as $user) {
            if($user->getIp() == $ip && $user->getPort() == $port) {
                return $user;
            }
        }
        return 0;
    }
}
