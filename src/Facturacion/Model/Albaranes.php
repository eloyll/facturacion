<?php


namespace Facturacion\Model;


use Facturacion\DAO\AlbaranesDAO;
use Facturacion\DAO\ClientesDAO;
use Facturacion\DataSource\Transacciones;

class Albaranes {

    private $albaranesDAO;
    private $transacciones;
    private $clientesDAO;
    private $validaciones;

    public function __construct(AlbaranesDAO $albaranesDAO, Transacciones $transacciones, ClientesDAO $clientesDAO, Validaciones $validaciones) {

        $this->albaranesDAO = $albaranesDAO;
        $this->transacciones = $transacciones;
        $this->clientesDAO = $clientesDAO;
        $this->validaciones = $validaciones;
    }

    public function grabarAlbaran(array $d){

        $emp = json_decode($d['albadatos'],true);
        $cli = json_decode($d['albacliente'],true);
        $ite = json_decode($d['albaitems'],true);
        $a = [];
        $a[0] = $emp['id_empresa'];
        $a[1] = $cli['cif'];
        $a[2] = $emp['numero_alba'];
        $a[3] = $emp['fecha_alba'];
        $a[11] = $cli['recoje'];
        $this->transacciones->startTransaction();
        foreach ($ite as $i => $v){
            $a[4] = $ite[$i]['cantidad'];
            $a[5] = $ite[$i]['codigo'];
            $a[6] = $ite[$i]['concepto'];
            $a[7] = $ite[$i]['descuento'];
            $a[8] = $ite[$i]['iva'];
            $a[9] = $ite[$i]['precio'];
            $a[10] = $ite[$i]['importe'];
            $r = $this->albaranesDAO->insertAlbaran($a);
            if($r['ok'] == 'no'){
                $this->transacciones->stopTransaction();
                return $r;
            }
        }
        $this->transacciones->grabarTransaction();

        return $r;
    }

    public function getAlbaran(array $d){

        $r['items'] = $this->albaranesDAO->selectAlbaran($d);
        $r['cliente'] = $this->clientesDAO->selectClienteCif($r['items'][0]['cliente_cif']);
        $r['cliente']['poblacion'] = ucwords(strtolower($r['cliente']['poblacion']));
        $r['albaran'] = ["fecha_alba"=>$r['items'][0]['fecha_alba'], "numero_alba"=>$r['items'][0]['numero_alba'], "firma"=>$r['items'][0]['nombre']];
        $r['empresa'] = ["nombre"=>$_SESSION['EMP-NOMBRE'],
                        "cif"=>$_SESSION['EMP-CIF'],
                        "logo"=>$_SESSION['EMP-LOGO'],
                        "dire"=>$_SESSION['EMP-CALLE']."\n".$_SESSION['EMP-CP']." - ".ucwords($_SESSION['EMP-CIUDAD'])."\n".$_SESSION['EMP-PROVINCIA']." (".ucwords($_SESSION['EMP-PAIS']).")"."\nTelf.".$_SESSION['EMP-TELF']." - ".$_SESSION['EMP-MOVIL']."\n".$_SESSION['EMP-EMAIL']."\n".$_SESSION['EMP-WEB'],
                        "decimales"=>$_SESSION['EMP-CF_DECIMALES'],
                        "moneda"=>$_SESSION['EMP-CF_MO_SIMBOLO'],
                        "registro_mercantil"=>$_SESSION['EMP-REGISTRO_MERCANTIL'],
                        "tipo_iva"=>$_SESSION['EMP-TIPO_IVA']];
        return $r;
    }

    public function getAlbaranescif(array  $d){

        $r['albaranes'] = $this->albaranesDAO->selectAlbaranCif($d['clicif']);
        $r['empresa'] = ["nombre"=>$_SESSION['EMP-NOMBRE'],
                        "logo"=>$_SESSION['EMP-LOGO'],
                        "moneda"=>$_SESSION['EMP-CF_MO_SIMBOLO'],
                        "decimales"=>$_SESSION['EMP-CF_DECIMALES']];
        $r['cliente'] = $this->clientesDAO->selectClienteCif($d['clicif']);

        return $r;
    }

    public function validarDatosAlbaran(array $d){

        $r = $this->validaciones->validarDatosAlbaran($d);
        if($r['ok'] == 'no'){
            return $r;
        }

        $r = $this->clientesDAO->selectClienteCif($d['albacif']);
        if($r['nl'] < 1){
            $r['ok'] = 'no';
            $r['id'] = 'albacif';
            return $r;
        }

        return $r;
    }
}