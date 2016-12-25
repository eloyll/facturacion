<?php

namespace Facturacion\DAO;


class UsuariosDAO {

    private $db;

    public function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function anadirUsuarios(){

    }

    public function getUsuario($dni){

        $sel = "select id,usuario,dni,clave,clave_md5,nivel_aut from usuarios where dni='$dni'";
        $rsel = $this->db->query($sel);
        $r = $rsel->fetch_assoc();
        $r['nl'] = $rsel->num_rows;

        return $r;

    }
}