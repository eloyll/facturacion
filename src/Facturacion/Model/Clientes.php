<?php

namespace Facturacion\Model;


use Facturacion\DAO\ClientesDAO;
use Files\Sesiones\GestionSesion;

class Clientes {

    private $clientesDAO;
    private $validaciones;
    private $gestionsesion;

    public function __construct(ClientesDAO $clientesDAO, Validaciones $validaciones, GestionSesion $gestionsesion) {

        $this->clientesDAO = $clientesDAO;
        $this->validaciones = $validaciones;
        $this->gestionsesion = $gestionsesion;
    }

    public function clientesUsuarioList($idusu){

        $r = $this->clientesDAO->selectClientesList($idusu);

        return $r;
    }

    public function clienteCif($cif){

        $r = $this->clientesDAO->selectClienteCif($cif);

        return $r;
    }

    public function anadirCliente(array $d){
        $r = $this->validaciones->validarCliente($d);
        if($r['ok'] == 'no'){
            return $r;
        }

        $c = $this->clienteCif($r['cif']);
        if($c['nl'] >= 1){
            $r['ok'] = 'no';
            $r['id'] = 'cif';
            $r['nombre'] = $c['nombre'];
            $r['nl'] = $c['nl'];
            return $r;
        }
        unset($r['ok']);
        $s = $this->clientesDAO->insertCliente($r);

        return $s;
    }

    public function buscarClientes(array $d){
        $r = $this->clientesDAO->selectBuscaClientes($d);
        $r['datos'] = '';
        for($i=0;$i < $r['nl'];$i++){
            $r['datos'] .= '<option value="'.$r[$i]['id'].'" class="pointer">'.ucwords($r[$i]['nombre']).' - CIF. '.strtoupper($r[$i]['cif']).' - '.ucwords($r[$i]['poblacion']).'</option>';
        }

        return $r;
    }

    public function buscarClienteId(int $id){
        $r = $this->clientesDAO->selectClienteId($id);

        return $r;
    }

    public function modificarClienteId(array $d){
        $r = $this->clientesDAO->updateClienteId($d);

        return $r;
    }

}