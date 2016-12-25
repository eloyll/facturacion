<?php

namespace Facturacion\DAO;


class AuxFormasPagoDAO {

    private $db;

    function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function selectFormasPago(){
        $sel = "select forma_pago from aux_formas_pago order by forma_pago";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }

        return $r;
    }
}