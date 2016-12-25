<?php


namespace Facturacion\DAO;


class BancosDAO {

    private $db;

    public function __construct(\mysqli $db) {
        $this->db = $db;
    }


    public function selectBancosEmp($idemp){
        $sel = "select id_empresa,numero_cuenta,swift,nombre from bancos_empresas where id_empresa='$idemp'";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }
        $r['nl'] = $rsel->num_rows;


        return $r;
    }

}