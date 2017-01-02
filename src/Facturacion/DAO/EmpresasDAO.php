<?php


namespace Facturacion\DAO;


class EmpresasDAO {

    private $db;

    public function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function insertEmpresa(array $d){

        $ins = "insert into empresas (id_usuario, cif, nombre, calle, cp, ciudad, provincia, pais, telf, movil, web, email) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($ins);
        $stmt->bind_param('isssssssssss',$d['idusu'],$d['cif'],$d['nombre'],$d['calle'],$d['cp'],$d['ciudad'],$d['provincia'],$d['pais'],$d['telf'],$d['movil'],$d['web'],$d['email']);
        $r['st'] = $stmt->execute();
        if(!$r['st']){
            $r['ok'] = 'nob';
            $r['error'] = $stmt->error;
            $r['errno'] = $stmt->errno;
        }else{
            $r['ok'] = 'si';
            $r['id_empresa'] = $stmt->insert_id;
        }

        return $r;

    }

    public function selectEmpresas($id){

        $sel = "select * from empresas where id_usuario='$id'";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }
        $r['nl'] = $rsel->num_rows;
        return $r;
    }

    public function selectEmpresaInicial($id){

         $sel = "select id,id_usuario, cif, nombre, calle, cp, ciudad, provincia, pais, telf, movil, web, email, fecha_alta, ultima from empresas where id_usuario='$id' and ultima='si'";
        $rsel = $this->db->query($sel);
        if($rsel->num_rows < 1){
            $sel = "select id,id_usuario, cif, nombre, calle, cp, ciudad, provincia, pais, telf, movil, web, email, fecha_alta, ultima from empresas where id_usuario='$id' LIMIT 1";
            $rsel = $this->db->query($sel);
            $r = $rsel->fetch_assoc();
        }else{
            $r = $rsel->fetch_assoc();
        }


        return $r;
    }

    public function selectEmpresa($id){

        $sel = "select * from empresas where id='$id'";
        $rsel = $this->db->query($sel);
        if($rsel){
            $r = $rsel->fetch_assoc();
            $r['ok'] = 'si';
        }else{
            $r['ok'] = 'no';
        }


        return $r;
    }

    public function updateUltima($idusu,$id){

        $this->db->query("update empresas set ultima='no' where id_usuario='$idusu'");
        $this->db->query("update empresas set ultima='si' where id='$id'");

        return true;
    }
}