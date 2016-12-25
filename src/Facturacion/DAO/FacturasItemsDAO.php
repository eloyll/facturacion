<?php


namespace Facturacion\DAO;


class FacturasItemsDAO {

    private $db;
    private $albaranesDAO;

    public function __construct(\mysqli $db, AlbaranesDAO $albaranesDAO) {
        $this->db = $db;
        $this->albaranesDAO = $albaranesDAO;
    }

    public function isertItems(array $d,$idemp,$numfac,$decimales=2){

        foreach ($d as $k => $v){
            $cant = '';
            $cod = '';
            $con = '';
            $des = '';
            $iva = '';
            $pre = '';
            $imp = '';
            $idalba = '--';
            foreach ($v as $c => $x){
                switch ($c){
                    case 'cantidad':
                        $cant = number_format($x,2);
                        break;
                    case 'codigo':
                        $cod = $x;
                        break;
                    case 'concepto':
                        $con = $x;
                        break;
                    case 'descuento':
                        $des = number_format($x,2);
                        break;
                    case 'iva':
                        $iva = number_format($x,2);
                        break;
                    case 'precio':
                        $pre = number_format($x,$decimales);
                        break;
                    case 'importe':
                        $imp = number_format($x,$decimales);
                        break;
                    case 'idalba':
                        $idalba = $x;
                }
            }
            $ins = "insert into facturas_items (id_empresa, numero_fac, cantidad, codigo, concepto, descuento, iva, precio, importe) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $this->db->prepare($ins);
            $stmt->bind_param('issssssss',$idemp,$numfac,$cant,$cod,$con,$des,$iva,$pre,$imp);
            $r = $stmt->execute();
            if(!$r){
                $r['error'] = $stmt->error;
                break ;
            }
            if(is_numeric($idalba)){
                $it = $this->albaranesDAO->updateItemAlbaFact($idalba,$numfac);
                if(!$it){
                    $r['error'] = 'error update albaran';
                    break ;
                }
            }

        }
        return $r;
    }
}