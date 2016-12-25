<?php


namespace Facturacion\DAO;


class FacturasVenciDAO {

    private $db;

    public function __construct(\mysqli $db) {
        $this->db = $db;
    }

    public function insertCobros(array $d,$idemp,$numfac,$decimales){

        foreach ($d as $k => $v){
            $fecha = '';
            $tipo = '';
            $importe = '';
            $cuenta = '';
            $swift = '';
            foreach ($v as $c => $x){
                switch ($c){
                    case 'vfecha':
                        $fecha = $x;
                        break;
                    case 'vtipo':
                        $tipo = trim($x);
                        break;
                    case 'vimporte':
                        $importe = number_format($x,$decimales);
                        break;
                    case 'vbanco':
                        $cuenta = trim($x);
                        break;
                    case 'vswift':
                        $swift = trim($x);
                        break;
                }
            }
            $ins = "insert into facturas_venci (id_empresa, numero_fac, fecha, tipo, importe,numero_cuenta,swift) VALUES (?,?,?,?,?,?,?)";
            $stmt = $this->db->prepare($ins);
            $stmt->bind_param('issssss',$idemp,$numfac,$fecha,$tipo,$importe,$cuenta,$swift);
            $r = $stmt->execute();
            if(!$r){
                break;
            }
        }
        return $r;

    }
}