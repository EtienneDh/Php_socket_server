<?php

class User
{
    private $pseudo;
    private $ip;

    public function __construct(string $pseudo, string $ip)
    {
        $this->setPseudo($pseudo)->setIp($ip);
    }

    private function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    private function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    public function getPseudo()
    {
        return $this->pseudo;
    }

    public function getIp()
    {
        return $this->Ip;
    }


}
