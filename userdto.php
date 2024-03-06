<?php
class UserDTO
{
    private $id;
    private $nome;
    private $email;
    private $password;

    public function __construct($id, $nome, $email, $password = null)
    {
        $this->id = $id;
        $this->nome = $nome;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }
}

?>