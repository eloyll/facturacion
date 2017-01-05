<?php


namespace Facturacion\DAO;


class BancosDAO {

    private $db;

    public function __construct(\mysqli $db) {
        $this->db = $db;
    }


    public function selectBancosEmp($idemp){
        $sel = "select id_empresa,numero_cuenta,swift,banco from bancos_empresas where id_empresa='$idemp' and activo='si'";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }
        $r['nl'] = $rsel->num_rows;


        return $r;
    }

    public function insertBanco(array $d){
        $ins = "insert into bancos_empresas (id_empresa, numero_cuenta, swift, banco) VALUES (?,?,?,?)";
        $stmt = $this->db->prepare($ins);
        $stmt->bind_param('isss',$d['id_empresa'],$d['numero_cuenta'],$d['swift'],$d['banco']);
        $r['st'] = $stmt->execute();
        if(!$r['st']){
            $r['ok'] = 'no';
            $r['error'] = $stmt->error;
        }else{
            $r['ok'] = 'si';
        }

        return $r;
    }

}