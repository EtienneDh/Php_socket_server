<?php

class User
{
    private $pseudo = '';
    private $ip;
    private $port;
    private $msgSent = 0;

    public function __construct($ip, $port)
    {
        $this->setIp($ip)->setPort($port);
    }

    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    private function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    private function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getPort($port)
    {
        return $this->port;
    }

    public function getMsgSent()
    {
        return $this->msgSent;
    }

    public function incrementMsgSent()
    {
        $this->msgSent++;
    }


}
