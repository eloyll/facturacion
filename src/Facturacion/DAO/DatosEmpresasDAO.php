<?php


namespace Facturacion\DAO;


class DatosEmpresasDAO {

    private $db;

    public function __construct(\mysqli $db) {
        $this->db = $db;
    }

    public function insertDatos(array $d){

        $ins = "insert into datos_empresas (id_empresa, tipo_iva, iva, retencion, req_equi, prefijo_numfac, sufijo_numfac, numero_fac, registro_mercantil, exento_iva, texto_exento) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($ins);
        $stmt->bind_param('isdddssisss',$d['id_empresa'],$d['tipo_iva'],$d['iva'],$d['retencion'],$d['req_equi'],$d['prefijo_numfac'],$d['sufijo_numfac'],$d['numero_fac'],$d['registro_mercantil'],$d['exento_iva'],$d['texto_exento']);
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

    public function selectDatos($idemp){
        $sel = "select id as iddatos, id_empresa,tipo_iva,iva,retencion,req_equi,prefijo_numfac,sufijo_numfac,numero_fac,registro_mercantil,exento_iva,texto_exento from datos_empresas where id_empresa='$idemp'";
        $rsel = $this->db->query($sel);
        $r = $rsel->fetch_assoc();

        return $r;
    }

    public function nuevoNumeroFactura($idemp){
        //$this->db->query("START TRANSACTION");
        $sel = "select prefijo_numfac,numero_fac,sufijo_numfac from datos_empresas where id_empresa='$idemp' for UPDATE ";
        $rsel = $this->db->query($sel);
        $num = $rsel->fetch_assoc();
        $rup = $this->db->query("update datos_empresas set numero_fac = numero_fac+1 where id_empresa='$idemp'");
        if($rsel && $rup){
            //$this->db->query("COMMIT");
        }else{
            //$this->db->query("ROLLBACK");

            return 'error';
        }
        $nuevo = $num['numero_fac'] + 1;
        $nfactura = trim($num['prefijo_numfac'].$nuevo.$num['sufijo_numfac']);

        return $nfactura;



    }

}