<?php
/**
 * Created by PhpStorm.
 * User: eloy
 * Date: 10/11/16
 * Time: 12:47
 */

namespace Facturacion\Model;


use Facturacion\DAO\ConfiguracionesDAO;
use Facturacion\DAO\DatosEmpresasDAO;
use Facturacion\DAO\EmpresasDAO;
use Facturacion\DAO\LogosDAO;
use Files\Sesiones\GestionSesion;

class Empresas {

    private $empresasDAO;
    private $gestionsesion;
    private $datosempresasDAO;
    private $configuracionesDAO;
    private $bancos;
    private $logos;

    public function __construct(EmpresasDAO $empresasDAO, DatosEmpresasDAO $datosempresasDAO, GestionSesion $gestionsesion, ConfiguracionesDAO $configuracionesDAO, Bancos $bancos, Logos $logos) {

        $this->empresasDAO = $empresasDAO;
        $this->gestionsesion = $gestionsesion;
        $this->datosempresasDAO = $datosempresasDAO;
        $this->configuracionesDAO = $configuracionesDAO;
        $this->bancos = $bancos;
        $this->logos = $logos;
    }

    public function putEmpresaSesion(array $d){
        foreach ($d as $k => $v){
            $key = "EMP-".strtoupper($k);
            $this->gestionsesion->setKey($key,$v);
        }

        return true;
    }

    public function empresaInicial($idusu){

        $r1 = $this->empresasDAO->selectEmpresaInicial($idusu);
        unset($r1['fecha_alta']);
        $r2 = $this->datosEmpresas($r1['id']);
        $r3 = $this->configuracionesDAO->selectConfiguracion($r1['id']);
        $r4 = $this->logos->logoInicial($r1['id']);
        $r = array_merge($r1,$r2,$r3,$r4);
        $this->putEmpresaSesion($r);

        $r['fecha_factura'] = date("d/m/Y");

        return $r;
    }

    public function datosEmpresas($id){
        $r = $this->datosempresasDAO->selectDatos($id);

        return $r;
    }

    public function empresasUsuario($idusu){

        $r = $this->empresasDAO->selectEmpresas($idusu);

        return $r;
    }

    public function cambioEmpresa($idemp){

        $r1 = $this->empresasDAO->selectEmpresa($idemp);
        unset($r1['fecha_alta']);
        $r2 = $this->datosEmpresas($r1['id']);
        $this->empresasDAO->updateUltima($r1['id_usuario'],$r1['id']);
        $r3 = $this->configuracionesDAO->selectConfiguracion($r1['id']);
        $r = array_merge($r1,$r2,$r3);
        $this->putEmpresaSesion($r);
        $bancos = $this->bancos->getBancosEmp($r1['id']);
        $r['bancos'] = '<option value="--" selected="">Selcciona el Banco</option>';
        for($i=0;$i < count($bancos); $i++){
            if(is_null($bancos[$i]['numero_cuenta'])){
                continue;
            }
            $r['bancos'] .= '<option value="'.$bancos[$i]['numero_cuenta'].'#'.$bancos[$i]['swift'].'">'.$bancos[$i]['nombre'].' - '.trim(substr($bancos[$i]['numero_cuenta'],-10)).'</option>';
        }
        $logos = $this->logos->logosEmpresa($r1['id']);
        for($j=0;$j < count($logos);$j++){
            $opt = explode('/',$logos[$j]['logo']);
            $chk = '';
            if($logos[$j]['ultimo'] == 'si'){
                $chk = 'selected';
                $this->gestionsesion->setKey('EMP-LOGO',$logos[$j]['logo']);
                $r['logo'] = $logos[$j]['logo'];
            }
            $r['logos'] .= '<option value="'.$logos[$j]['logo'].'" '.$chk.'>'.$opt[count($opt)-1].'</option>';
        }

        $r['fecha_factura'] = date("d/m/Y");
        $r['numero_fac'] = $r['numero_fac'] + 1;

        return $r;

    }

    public function cambioLogo(array $d){
        $r = $this->logos->cambioLogoEmpresa($d);
        $this->gestionsesion->setKey('EMP-LOGO',$d['logo']);
        $r['ok'] = 'si';

        return $r;
    }
}