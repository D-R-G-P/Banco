<?php

require_once 'db.php';

class User extends DB
{
    private $nombre;
    private $apellido;
    private $cargo;
    private $tipo_usuario;

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
        $query = $this->connect()->prepare('SELECT nombre, apellido, cargo, tipo_usuario FROM users WHERE username = :user');
        $query->execute(['user' => $user]);

        if ($query->rowCount()) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $this->nombre = $result['nombre'];
            $this->apellido = $result['apellido'];
            $this->cargo = $result['cargo'];
            $this->tipo_usuario = $result['tipo_usuario'];
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
    public function getTipo_usuario() {
        return $this->tipo_usuario;
    }
}
