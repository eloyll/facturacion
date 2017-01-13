<?php

namespace Facturacion\Model;


use Facturacion\DAO\AuxFormasPagoDAO;
use Facturacion\DAO\AuxProvinciasDAO;
use Files\Sesiones\GestionSesion;

class Main {

    private $gestionsesion;
    private $empresas;
    private $clientes;
    private $bancos;
    private $logos;
    private $auxprovinciasDAO;
    private $auxformaspago;
    private $validaciones;

    public function __construct(GestionSesion $gestionsesion, Empresas $empresas, Clientes $clientes, Bancos $bancos, Logos $logos, AuxProvinciasDAO $auxprovinciasDAO, AuxFormasPago $auxformaspago, Validaciones $validaciones) {

        $this->gestionsesion = $gestionsesion;
        $this->empresas = $empresas;
        $this->clientes = $clientes;
        $this->bancos = $bancos;
        $this->logos = $logos;
        $this->auxprovinciasDAO = $auxprovinciasDAO;
        $this->auxformaspago = $auxformaspago;
        $this->validaciones = $validaciones;
    }

    public function inicio(){

        $d = [];
        $d['idusu'] = $this->gestionsesion->getKey('FAC-IDUSU');
        $d['usuario'] = $this->gestionsesion->getKey('FAC-USUARIO');
        $d['empresa'] = $this->empresas->empresaInicial($d['idusu']);
        $d['empre'] = $this->empresas->empresasUsuario($d['idusu']);
        $d['clientes'] = $this->clientes->clientesUsuarioList($d['idusu']);
        $d['bancos'] = $this->bancos->getBancosEmp($d['empresa']['id']);
        $d['logos'] = $this->logos->logosEmpresa($d['empresa']['id']);
        $d['formaspago'] = $this->auxformaspago->getFormasPago();

        return $d;
    }

    public function hacerAlbaran(){

        $d = [];
        $d['idusu'] = $this->gestionsesion->getKey('FAC-IDUSU');
        $d['usuario'] = $this->gestionsesion->getKey('FAC-USUARIO');
        $d['empresa'] = $this->empresas->empresaInicial($d['idusu']);
        unset($d['empresa']['prefijo_numfac'],$d['empresa']['sufijo_numfac'],$d['empresa']['numero_fac']);
        $d['empresa']['numeroalba'] = substr(time(),-7);
        $d['empre'] = $this->empresas->empresasUsuario($d['idusu']);
        $d['clientes'] = $this->clientes->clientesUsuarioList($d['idusu']);

        return $d;
    }

    public function clientes(array $d){
        $r['idusu'] = $this->gestionsesion->getKey('FAC-IDUSU');
        $r['usuario'] = $this->gestionsesion->getKey('FAC-USUARIO');
        $r['provincias'] = $this->auxprovinciasDAO->selectProvincias();
        $r['formaspago'] = $this->auxformaspago->getFormasPago();
        $r['id'] = $d['id'];

        return $r;
    }

    public function validarDatosFactura(array $d){
        $r = $this->validaciones->validarDatos($d);
        if($r['ok'] == 'no'){
            return $r;
        }
        $r = $this->clientes->clienteCif($d['factcif']);
        if($r['nl'] < 1){
            $r['ok'] = 'no';
            $r['id'] = 'factcif';
            return $r;
        }

        return $r;
    }

    public function empresas(){
        $r['idusu'] = $this->gestionsesion->getKey('FAC-IDUSU');
        $r['usuario'] = $this->gestionsesion->getKey('FAC-USUARIO');
        $r['provincias'] = $this->auxprovinciasDAO->selectProvincias();

        return $r;
    }

    public function validarDatosEmpresa (array $d){
        $v = $this->validaciones->validarEmpresa($d['empresa']);
        if($v['ok'] == 'no'){
            return $v;
        }
        $v1 = $this->validaciones->validarEmpresa($d['datos_iva']);
        if($v1['ok'] == 'no'){
            return $v1;
        }
        $v2 = $this->validaciones->validarEmpresa($d['config']);
        if($v2['ok'] == 'no'){
            return $v2;
        }
        $v3 = $this->validaciones->validarEmpresa($d['banco']);
        if($v3['ok'] == 'no'){
            return $v3;
        }

        if(!empty($d['logo']['nombre'])){
            $l = $this->logos->existeLogo($d['logo']['nombre']);
            if($l['ok'] == 'no'){
                $l['ok'] = 'logo';
                return $l;
            }
        }
        $r = $this->empresas->anadirEmpresa($v, $v1, $v2, $d['logo'], $v3);
        if($r['ok'] == 'no'){
            return $r;
        }

        return $r;
    }

    public function validarModificarEmpresa(array $d){
        $v = $this->validaciones->validarEmpresa($d['empresa']);
        if($v['ok'] == 'no'){
            return $v;
        }
        $v1 = $this->validaciones->validarEmpresa($d['datos_iva']);
        if($v1['ok'] == 'no'){
            return $v1;
        }
        $v2 = $this->validaciones->validarEmpresa($d['config']);
        if($v2['ok'] == 'no'){
            return $v2;
        }
        $r = $this->empresas->modificarEmpresa($v,$v1,$v2);

        return $r;
    }

    public function validarModificarBanco(array $d){
        $v = $this->validaciones->validarEmpresa($d['banco']);
        if($v['ok'] == 'no'){
            return $v;
        }
        $r = $this->bancos->modifcaBanco($v);
        if($r['ok'] != 'no'){
            $r['bancos'] = $this->empresas->divBancosModi($r['id_empresa']);
        }

        return $r;
    }

    public function borraBanco(array $d){
        $r = $this->bancos->borraBanco($d['numero_cuenta']);
        $r['bancos'] = $this->empresas->divBancosModi($d['id_empresa']);

        return $r;
    }

    public function nuevoLogo(array $d){

        $l = $this->logos->existeLogo($d['logo']['nombre']);
        if($l['ok'] == 'no'){
            $l['ok'] = 'logo';
            return $l;
        }

        $r = $this->logos->anadirLogo($d['logo']);
        if($r['ok'] == 'no'){
            return $r;
        }
        $r['logos'] = $this->empresas->divLogosModi($d['logo']['id_empresa']);

        return $r;
    }

    public function borraLogoId(array $d){
        $r = $this->logos->borraLogoId($d['id']);
        if($r['ok'] == 'no'){
            return $r;
        }
        $r['logos'] = $this->empresas->divLogosModi($d['id_empresa']);

        return $r;
    }

    public function modificarClienteId(array $d){
        $v = $this->validaciones->validarEmpresa($d);
        if($v['ok'] == 'no'){
            return $v;
        }
        $r = $this->clientes->modificarClienteId($v);

        return $r;
    }



}