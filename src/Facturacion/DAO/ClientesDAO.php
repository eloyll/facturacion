<?php

namespace Facturacion\DAO;


class ClientesDAO {

    private $db;

    public function __construct(\mysqli $db) {

        $this->db = $db;

    }

    public function selectClientesList($idusu){

        $sel = "select id,cif,nombre from clientes where id_usuario='$idusu'";
        $rsel = $this->db->query($sel);
        $r = [];
        for($i=0;$i<$rsel->num_rows;$i++){
            $r[] = $rsel->fetch_assoc();
        }
        $r['nl'] = $rsel->num_rows;
        return $r;
    }

    public function selectClienteCif(string $cif){

        $sel = "select * from clientes where cif='$cif'";
        $rsel = $this->db->query($sel);
        if($rsel){
            $r = $rsel->fetch_assoc();
            $r['ok'] = 'si';
            $r['nl'] = $rsel->num_rows;
        }else{
            $r['ok'] = 'no';
        }

        return $r;
    }

    public function insertCliente(array $d){
        $ins = "insert into clientes (id_usuario, cif, nombre, direccion, poblacion, cp, provincia, pais, web, email, obser, descuento, forma_pago, movil, telf,contacto) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($ins);
        $stmt->bind_param('issssssssssdssss',$d['idusu'],$d['cif'],$d['nombre'],$d['direccion'],$d['poblacion'],$d['cp'],$d['provincia'],$d['pais'],$d['web'],$d['email'],$d['obser'],$d['descuento'],$d['forma_pago'],$d['movil'],$d['telf'],$d['contacto']);
        $r['st'] = $stmt->execute();
        if(!$r['st']){
            $r['ok'] = 'no';
            $r['error'] = $stmt->error;
        }else{
            $r['ok'] = 'si';
        }

        return $r;
    }


}