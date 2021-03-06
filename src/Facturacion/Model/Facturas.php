<?php

namespace Facturacion\Model;


use Facturacion\DAO\DatosEmpresasDAO;
use Facturacion\DAO\FacturasDAO;
use Facturacion\DataSource\Transacciones;
use Files\Sesiones\GestionSesion;

class Facturas {

    private $facturasDAO;
    private $datosempresasDAO;
    private $gestionsesion;
    private $transacciones;
    private $logos;

    public function __construct(FacturasDAO $facturasDAO, DatosEmpresasDAO $datosEmpresasDAO,GestionSesion $gestionsesion,Transacciones $transacciones, Logos $logos ) {

        $this->facturasDAO = $facturasDAO;
        $this->datosempresasDAO = $datosEmpresasDAO;
        $this->gestionsesion = $gestionsesion;
        $this->transacciones = $transacciones;
        $this->logos = $logos;
    }

    public function hacerFactura(array $d){

        $this->transacciones->startTransaction();
        $datos = json_decode($d['facturadatos'],true);
        if($datos['tipo-fac'] == 'factura') {
            $numfac = $this->datosempresasDAO->nuevoNumeroFactura($datos['idempresa']);
            if ($numfac == 'error') {
                $this->transacciones->stopTransaction();
                $r['ok'] = 'no';
                return $r;
            }
            $datos['numero_fact'] = $numfac;
        }else{
            $datos['numero_fact'] = $datos['num_fact'];
        }
        $cli = json_decode($d['facturacliente'],true);
        $iva = json_decode($d['facturaiva'],true);
        $venci = json_decode($d['facturavenci'],true);
        $items = json_decode($d['facturaitems'],true);

        $dec = $_SESSION['EMP-CF_DECIMALES'];

        $datos['empresa'] = $_SESSION['EMP-NOMBRE'];
        $datos['cif'] = strtoupper($_SESSION['EMP-CIF']);
        $datos['direccion'] = $_SESSION['EMP-CALLE']."\n".$_SESSION['EMP-CP'].' - '.$_SESSION['EMP-CIUDAD']."\n".ucwords(strtolower($_SESSION['EMP-PROVINCIA']));
        $datos['direccion'].= ' - ('.ucwords($_SESSION['EMP-PAIS']).")\n Telfs. ".$_SESSION['EMP-TELF'].' - '.$_SESSION['EMP-MOVIL']."\n";
        $datos['direccion'].= 'Email: '.$_SESSION['EMP-EMAIL']."\n".$_SESSION['EMP-WEB'];
        $datos['logo'] = $_SESSION['EMP-LOGO'];
        $datos['registro_mercantil'] = $_SESSION['EMP-REGISTRO_MERCANTIL'];
        $datos['cliente'] = $cli['nombre'];
        $datos['cif_cliente'] = $cli['cif'];
        $datos['direc_cliente'] = str_ireplace("<br>","\n",$cli['direccion']);
        $datos['obser'] = $cli['obserfactura'];
        $datos['importe'] = number_format($datos['importe'],$dec);
        $datos['iva'] = 0;
        foreach ($iva as $k => $v){
            if(substr($k,0,2) == 'bi'){
                continue;
            }
            $datos['iva'] = $datos['iva'] + $v;
        }
        $datos['iva'] = $datos['iva'] + $datos['recargo'];
        $datos['tipo_iva'] = $_SESSION['EMP-TIPO_IVA'];
        $datos['iva'] = number_format($datos['iva'],$dec);
        $datos['pctge_retencion'] = number_format($_SESSION['EMP-RETENCION'],2);
        $datos['retencion'] = number_format($datos['retencion'],2);
        $datos['pctge_req'] = number_format($_SESSION['EMP-REQ_EQUI'],2);
        $datos['recargo_equ'] = number_format($datos['recargo'],2);
        $datos['total'] = number_format($datos['total'],$dec);
        $datos['moneda'] = $_SESSION['EMP-CF_MO_SIMBOLO'];
        $datos['decimales'] = $_SESSION['EMP-CF_DECIMALES'];
        $datos['ivas'] = array_chunk($iva,2,true);
        $datos['venci'] = $venci;
        $datos['items'] = $items;
        $datos['jash'] = md5($datos['idempresa'].$datos['numero_fact'].$datos['total']);

        $l = $this->logos->actualizaCampoFactura($datos['logo']);
        if($l['ok'] == 'no'){
            $this->transacciones->stopTransaction();
            $r['ok'] = 'no';
        }

        $f = $this->facturasDAO->insertFactura($datos);
        if($f['ok'] == 'si'){
            $r['ok'] = 'si';
            $r['numero_fact'] = $datos['numero_fact'];
            $r['id_empresa'] = $datos['idempresa'];
            $r['tipo-fac'] = $datos['tipo-fac'];
            $this->transacciones->grabarTransaction();
        }else{
            $this->transacciones->stopTransaction();
            $r['ok'] = 'no';
        }
        return $r;
    }

    public function getFactura(array $d){

        $r = $this->facturasDAO->selectFactura($d);

        return $r;
    }
}