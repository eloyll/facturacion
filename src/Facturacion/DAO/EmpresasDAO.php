<?php


namespace Facturacion\DAO;


class EmpresasDAO {

    private $db;

    public function __construct(\mysqli $db) {

        $this->db = $db;
    }

    public function insertEmpresa(array $d){

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

        $sel = "select * from empresas where id_usuario='$id' and ultima='si'";
        $rsel = $this->db->query($sel);
        $r = $rsel->fetch_assoc();

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