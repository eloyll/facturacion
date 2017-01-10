<?php


namespace Facturacion\DAO;


class BancosDAO {

    private $db;

    public function __construct(\mysqli $db) {
        $this->db = $db;
    }


    public function selectBancosEmp($idemp){
        $sel = "select id_empresa,numero_cuenta,swift,banco,activo from bancos_empresas where id_empresa='$idemp' and activo='si'";
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

    public function selectAllBancosEmp($idemp){
        $sel = "select id_empresa,numero_cuenta,swift,banco,activo from bancos_empresas where id_empresa='$idemp'";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }
        $r['nl'] = $rsel->num_rows;


        return $r;
    }

    public function upadteBanco(array $d){
        $rpl = "replace into bancos_empresas (id_empresa, numero_cuenta, swift, banco, activo) VALUES (?,?,?,?,?)";
        $stmt = $this->db->prepare($rpl);
        $stmt->bind_param('issss',$d['id_empresa'],$d['numero_cuenta'],$d['swift'],$d['banco'],$d['activo']);
        $r['st'] = $stmt->execute();
        if(!$r['st']){
            $r['ok'] = 'no';
            $r['error'] = $stmt->error;
        }else{
            $r['ok'] = 'si';
            $r['id_empresa'] = $d['id_empresa'];
        }

        return $r;
    }

    public function deleteBanco(string $cuenta){
        $del = "delete from bancos_empresas where numero_cuenta='$cuenta'";
        $rdel = $this->db->query($del);
        if(!$rdel){
            $r['ok'] = 'no';
            $r['error'] = $this->db->errno;
        }
        $r['ok'] = 'si';

        return $r;
    }

}