<?php


namespace Files\Sesiones;


class GestionSesion {


    public function setKey($key,$valor) {
        $_SESSION[$key] = $valor;
    }


    public function existeKey($key) {

        return array_key_exists($key,$_SESSION);

    }

    public function getKey($key){

        if($this->existeKey($key)){
            return $_SESSION[$key];
        }else{
            return 'error';
        }
    }

    public function borraKey($key){

        if(isset($_SESSION[$key])){
            unset($_SESSION[$key]);
        }
    }

    public function borraSesion(){
        $cuki = session_get_cookie_params();
        setcookie(session_name(), 0, 1, $cuki["path"]);
        session_destroy();
        unset($_SESSION);

        return true;
    }


}