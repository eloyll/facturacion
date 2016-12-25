<?php


namespace Facturacion\DAO;


class FacturasIvaDAO {

    private $db;

    public function __construct(\mysqli $db) {
        $this->db = $db;
    }

    public function insertIva(array $d,$idemp,$numfac){

        foreach ($d as $k => $v){
            $piva = '';
            $biva = '';
            $iva = '';
            foreach ($v as $c => $x){
                $exiva = explode('_',$c);
                if(count($exiva) === 1){
                    $piva = number_format($c,2);
                    $iva = number_format($x,2);
                }else{
                    $biva = number_format($x,2);
                }
            }
            $ins = "insert into facturas_iva (id_empresa, numero_fac, pctge_iva, bi_iva, iva) VALUES (?,?,?,?,?)";
            $stmt = $this->db->prepare($ins);
            $stmt->bind_param('issss',$idemp,$numfac,$piva,$biva,$iva);
            $r = $stmt->execute();
            if(!$r){
                break;
            }
        }
        return $r;

    }

}