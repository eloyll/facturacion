<?php


namespace Facturacion\DAO;


class LogosDAO {

    private $db;

    public function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function selectLogoInicial($idemp){
        $sel = "select logo from logos_empresas where id_empresa='$idemp' and ultimo='si'";
        $rsel = $this->db->query($sel);
        $r = $rsel->fetch_assoc();

        return $r;
    }

    public function selectLogosEmp($idemp){
        $sel = "select id,logo,ultimo,nombre from logos_empresas where id_empresa='$idemp'";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }

        return $r;
    }

    public function updateUltimoLogo(array $d){
        $this->db->query("update logos_empresas set ultimo='no' where id_empresa='$d[idemp]'");
        $this->db->query("update logos_empresas set ultimo='si' where id_empresa='$d[idemp]' and logo='$d[logo]'");
        $r['ok'] = 'si';

        return $r;
    }

    public function insertLogo(array $d){
        $ins = "insert into logos_empresas (id_empresa, logo, nombre) VALUES (?,?,?)";
        $stmt = $this->db->prepare($ins);
        $stmt->bind_param('iss',$d['id_empresa'],$d['base64'],$d['nombre']);
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