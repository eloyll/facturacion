<?php


namespace Facturacion\DAO;


class ConfiguracionesDAO {

    private $db;

    public function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function selectConfiguracion($idemp){
        $sel = "select id_empresa, cf_moneda, cf_mo_simbolo, cf_decimales from configuraciones where id_empresa='$idemp'";
        $rsel = $this->db->query($sel);
        $r = $rsel->fetch_assoc();

        return $r;
    }
    public function insertConfiguracion(array $d){
        $ins = "insert into configuraciones (id_empresa, cf_moneda, cf_mo_simbolo, cf_decimales) VALUES (?,?,?,?)";
        $stmt = $this->db->prepare($ins);
        $stmt->bind_param('isss',$d['id_empresa'],$d['cf_moneda'],$d['cf_mo_simbolo'],$d['cf_decimales']);
        $r['st'] = $stmt->execute();
        if(!$r['st']){
            $r['ok'] = 'nob';
            $r['error'] = $stmt->error;
            $r['errno'] = $stmt->errno;
        }else{
            $r['ok'] = 'si';
        }

        return $r;
    }

}