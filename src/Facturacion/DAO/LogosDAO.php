<?php


namespace Facturacion\DAO;


class LogosDAO {

    private $db;

    public function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function selectLogoInicial($idemp){
        $sel = "select id as idlogo,logo from logos_empresas where id_empresa='$idemp' ORDER BY ultimo DESC limit 1";
        $rsel = $this->db->query($sel);
        $r = $rsel->fetch_assoc();

        return $r;
    }

    public function selectLogoId($id){
        $sel = "select logo from logos_empresas where id='$id'";
        $rsel = $this->db->query($sel);
        $l = $rsel->fetch_assoc();
        $r = $l['logo'];

        return $r;
    }

    public function selectLogosEmp($idemp){
        $sel = "select id as idlogo,logo,ultimo,nombre from logos_empresas where id_empresa='$idemp'";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }
        $r['nl'] = $rsel->num_rows;

        return $r;
    }

    public function updateUltimoLogo(array $d){
        $this->db->query("update logos_empresas set ultimo=0 where id_empresa='$d[idemp]'");
        $this->db->query("update logos_empresas set ultimo=1 where id_empresa='$d[idemp]' and logo='$d[logo]'");
        $r['ok'] = 'si';

        return $r;
    }

    public function insertLogo(array $d){
        $ins = "insert into logos_empresas (id_empresa, logo, nombre) VALUES (?,?,?)";
        $stmt = $this->db->prepare($ins);
        $stmt->bind_param('iss',$d['id_empresa'],$d['ruta'],$d['nombre']);
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

    public function deleteLogoId(int $id){
        $del = "delete from logos_empresas where id='$id'";
        $rdel = $this->db->query($del);
        if(!$rdel){
            $r['ok'] = 'no';
            $r['error'] = $this->db->errno;
        }else{
            $r['ok'] = 'si';
        }

        return $r;
    }
}