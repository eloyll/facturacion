<?php
/**
 * Created by PhpStorm.
 * User: eloy
 * Date: 13/11/16
 * Time: 12:24
 */

namespace Facturacion\Model;


use Facturacion\DAO\ClientesDAO;

class Clientes {

    private $clientesDAO;

    public function __construct(ClientesDAO $clientesDAO) {

        $this->clientesDAO = $clientesDAO;
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

        $r = $this->clientesDAO->insertCliente($d);

        return $r;
    }

}