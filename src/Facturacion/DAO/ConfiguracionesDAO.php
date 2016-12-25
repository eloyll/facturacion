<?php


namespace Facturacion\DAO;


class ConfiguracionesDAO {

    private $db;

    public function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function selectConfiguracion($idemp){
        $sel = "select * from configuraciones where id_empresa='$idemp'";
        $rsel = $this->db->query($sel);
        $r = $rsel->fetch_assoc();

        return $r;
    }

}