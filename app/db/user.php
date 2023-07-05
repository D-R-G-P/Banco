<?php

require_once 'db.php';

class User extends DB
{
    private $nombre;
    private $apellido;
    private $cargo;
    private $tipo_usuario;
    private $dni;
    private $username;
    private $banco;
    private $pass;

    public function userExists($user, $pass)
    {
        $md5pass = md5($pass);

        $query = $this->connect()->prepare('SELECT * FROM users WHERE username = :user AND password = :pass');
        $query->execute(['user' => $user, 'pass' => $md5pass]);

        if ($query->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    public function setUser($user)
    {
        $query = $this->connect()->prepare('SELECT nombre, apellido, password, cargo, tipo_usuario, dni, username, banco FROM users WHERE username = :user');
        $query->execute(['user' => $user]);

        if ($query->rowCount()) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $this->nombre = $result['nombre'];
            $this->apellido = $result['apellido'];
            $this->pass = $result['password'];
            $this->cargo = $result['cargo'];
            $this->tipo_usuario = $result['tipo_usuario'];
            $this->dni = $result['dni'];
            $this->username = $result['username'];
            $this->banco = $result['banco'];
        }
    }

    public function getNombre() {
        return $this->nombre;
    }
    public function getApellido() {
        return $this->apellido;
    }
    public function getCargo() {
        return $this->cargo;
    }
    public function getDni() {
        return $this->dni;
    }
    public function getUsername() {
        return $this->username;
    }
    public function getBanco() {
        return $this->banco;
    }
    public function getTipo_usuario() {
        return $this->tipo_usuario;
    }
    public function getPassword() {
        return $this->pass;
    }
}
