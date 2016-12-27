<?php

namespace Facturacion\Model;


use Facturacion\DAO\ClientesDAO;

class Clientes {

    private $clientesDAO;
    private $validaciones;

    public function __construct(ClientesDAO $clientesDAO, Validaciones $validaciones) {

        $this->clientesDAO = $clientesDAO;
        $this->validaciones = $validaciones;
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
        for($i=0;$i<count($d)-1;$i++){
            if($d[$i]['name'] == 'cif'){
                $cifcli = $d[$i]['value'];
            }
        }
        $c = $this->clienteCif($cifcli);
        if($c['nl'] >= 1){
            $r['ok'] = 'no';
            $r['id'] = 'cif';
            $r['nombre'] = $c['nombre'];
            $r['nl'] = $c['nl'];
            return $r;
        }
        //$r = $this->clientesDAO->insertCliente($d);

        return $r;
    }

}