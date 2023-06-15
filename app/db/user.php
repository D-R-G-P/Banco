<?php 

require_once 'db.php';

class User extends DB {
    private $nombre;
    private $username;
    private $apellidos;
    private $permisos;

    public function userExists($user, $pass) {
        $md5pass = md5($pass);

        $query = $this->connect()->prepare('SELECT * FROM users WHERE username = :user AND password = :pass');
        $query->execute(['user' => $user, 'pass' => $md5pass]);

        if($query->rowCount()) {
            return true;
        } else {
            return false;
        }
    }

    public function setUser($user){
        $query = $this->connect()->prepare('SELECT * FROM users WHERE username = :user');
        $query->execute(['user' => $user]);

        // foreach ($query as $currentUser) {
        //     $this->nombre = $currentUser['nombres'];
        //     $this->apellidos = $currentUser['apellidos'];
        //     $this->permisos = $currentUser['permisos'];
        // }
    }

    // public function getNombre() {
    //     return $this->nombre;
    // }

    // public function getApellidos() {
    //     return $this->apellidos;
    // }

    // public function getUsername() {
    //   return $this->username;
    // }

    // public function getPermisos() {
    //     return $this->permisos;
    //   }
}