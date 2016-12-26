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

    public function anadirCliente(array $d){
        $r = $this->validaciones->validarCliente($d);

        return $r;
    }

}