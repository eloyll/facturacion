<?php


namespace Facturacion\DAO;


class AuxProvinciasDAO {

    private $db;

    public function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function selectProvincias(){

        $sel = "select provincia from aux_provincias order by provincia";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }

        return $r;
    }
}