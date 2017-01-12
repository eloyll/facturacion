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
use Facturacion\DataSource\Transacciones;
use Files\Sesiones\GestionSesion;

class Empresas {

    private $empresasDAO;
    private $gestionsesion;
    private $datosempresasDAO;
    private $configuracionesDAO;
    private $bancos;
    private $logos;
    private $transacciones;

    public function __construct(EmpresasDAO $empresasDAO, DatosEmpresasDAO $datosempresasDAO, GestionSesion $gestionsesion, ConfiguracionesDAO $configuracionesDAO, Bancos $bancos, Logos $logos, Transacciones $transacciones) {

        $this->empresasDAO = $empresasDAO;
        $this->gestionsesion = $gestionsesion;
        $this->datosempresasDAO = $datosempresasDAO;
        $this->configuracionesDAO = $configuracionesDAO;
        $this->bancos = $bancos;
        $this->logos = $logos;
        $this->transacciones = $transacciones;
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
            $r['bancos'] .= '<option value="'.$bancos[$i]['numero_cuenta'].'#'.$bancos[$i]['swift'].'">'.$bancos[$i]['banco'].' - '.trim(substr($bancos[$i]['numero_cuenta'],-10)).'</option>';
        }
        $logos = $this->logos->logosEmpresa($r1['id']);
        for($j=0;$j < $logos['nl'];$j++){
            $opt = explode('/',$logos[$j]['logo']);
            $chk = '';
            if($logos[$j]['ultimo'] == '1'){
                $chk = 'selected';
                $this->gestionsesion->setKey('EMP-LOGO',$logos[$j]['logo']);
                $r['logo'] = $logos[$j]['logo'];
            }
            $r['logos'] .= '<option value="'.$logos[$j]['logo'].'" '.$chk.'>'.$logos[$j]['nombre'].'</option>';
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

    public function anadirEmpresa(array $d, array $d1, array $d2, array $d3, array $d4){
        $this->transacciones->startTransaction();
        $r1 = $this->empresasDAO->insertEmpresa($d);
        if($r1['ok'] == 'no'){
            $this->transacciones->stopTransaction();
            return $r1;
        }
        $d1['id_empresa'] = $r1['id_empresa'];
        $r2 = $this->datosempresasDAO->insertDatos($d1);
        if($r2['ok'] == 'no'){
            $this->transacciones->stopTransaction();
            return $r2;
        }
        $d2['id_empresa'] = $r1['id_empresa'];
        $r3 = $this->configuracionesDAO->insertConfiguracion($d2);
        if($r3['ok'] == 'no'){
            $this->transacciones->stopTransaction();
            return $r3;
        }
        if(!empty($d3['base64'])){
            $d3['id_empresa'] = $r1['id_empresa'];
            $r4 = $this->logos->anadirLogo($d3);
            if($r4['ok'] == 'no'){
                $this->transacciones->stopTransaction();
                return $r4;
            }
        }

        if(!empty($d4['numero_cuenta'])){
            $d4['id_empresa'] = $r1['id_empresa'];
            $r5 = $this->bancos->anadirBanco($d4);
            if($r5['ok'] == 'no'){
                $this->transacciones->stopTransaction();
                return $r5;
            }
        }
        $this->transacciones->grabarTransaction();
        $r['ok'] = 'si';

        return $r;
    }

    public function buscarEmpresas(array $d){
        $d['idusu'] = $this->gestionsesion->getKey('FAC-IDUSU');
        $r = $this->empresasDAO->selectBuscaEmpresas($d);
        $r['datos'] = '';
        for($i=0;$i < $r['nl'];$i++){
            $r['datos'] .= '<option value="'.$r[$i]['id'].'" class="pointer">'.ucwords($r[$i]['nombre']).' - CIF. '.strtoupper($r[$i]['cif']).' - '.ucwords($r[$i]['ciudad']).'</option>';
        }

        return $r;
    }

    public function getEmpresaId(int $idemp){
        $e = $this->empresasDAO->selectEmpresa($idemp);
        if($e['ok'] == 'no'){
            return $e;
        }
        unset($e['ok']);
        $r['empresa'] = $e;
        $e = [];
        $r['datos'] = $this->datosEmpresas($idemp);
        $r['config'] = $this->configuracionesDAO->selectConfiguracion($idemp);
        $r['bancos'] = $this->divBancosModi($idemp);
        /*$logos = $this->logos->logosEmpresa($idemp);
        for($i=0;$i<$logos['nl'];$i++){
            $r['logos'] .= '
            <div style="float:left;background-image: url('.$logos[$i]['logo'].');width: 70px;height: 35px;background-size: 100% auto;background-repeat: no-repeat"><i class="fa fa-window-close cerrarventana" title="Eliminar Logo" onclick="borrarlogo(\''.$logos[$i]['id'].'\')"></i> </div>
            ';
        }*/
        $r['logos'] = $this->divLogosModi($idemp);
        $r['ok'] = 'si';
        return $r;
    }

    public function modificarEmpresa(array $d, array $d1, array $d2){
        $this->transacciones->startTransaction();
        $r1 = $this->empresasDAO->updateEmpresa($d);
        if($r1['ok'] == 'no'){
            $this->transacciones->stopTransaction();
            return $r1;
        }
        $d1['id_empresa'] = $d['id'];
        $r2 = $this->datosempresasDAO->updateDatos($d1);
        if($r2['ok'] == 'no'){
            $this->transacciones->stopTransaction();
            return $r2;
        }
        $d2['id_empresa'] = $d['id'];
        $r3 = $this->configuracionesDAO->updateConfiguracion($d2);
        if($r3['ok'] == 'no'){
            $this->transacciones->stopTransaction();
            return $r3;
        }

        $this->transacciones->grabarTransaction();
        return $r3;
    }

    public function divBancosModi(string $id_empresa){
        $bancos = $this->bancos->getAllBancosEmp($id_empresa);
        $r = '';
        for($i=0;$i<$bancos['nl'];$i++){
            $tacha = '';
            if($bancos[$i]['activo'] == 'no'){
                $tacha = 'text-decoration: line-through';
            }
            $r .= '
            <div style="clear: both;float: left;width: 35%">'.$bancos[$i]['banco'].'</div>
                <div style="float: left;width: 40%;'.$tacha.'" title="Swift:'.$bancos[$i]['swift'].'">'.$bancos[$i]['numero_cuenta'].'</div>
                <div style="float: right;width: 15%;text-align: right">
                <i class="fa fa-pencil c-azul" title="Editar/Modificar" onclick="editabanco(\''.$bancos[$i]['banco'].'\',\''.$bancos[$i]['numero_cuenta'].'\',\''.$bancos[$i]['swift'].'\',\''.$bancos[$i]['activo'].'\')"></i>&nbsp;&nbsp;
                <i class="fa fa-close color-rojo" title="Eliminar" onclick="eliminabanco(\''.$bancos[$i]['numero_cuenta'].'\')"></i></div>
            ';
        }

        return $r;
    }

    public function divLogosModi(int $id_empresa){
        $logos = $this->logos->logosEmpresa($id_empresa);
        $r = '';
        for($i=0;$i<$logos['nl'];$i++){
            $r .= '
            <div style="float:left;background-image: url(\''.$logos[$i]['logo'].'\');width: 70px;height: 35px;background-size: 100% auto;background-repeat: no-repeat"><i class="fa fa-window-close cerrarventana" title="Eliminar Logo" onclick="borrarlogo(\''.$logos[$i]['id'].'\',\''.$logos[$i]['nombre'].'\')"></i> </div>
            ';
        }

        return $r;
    }

}