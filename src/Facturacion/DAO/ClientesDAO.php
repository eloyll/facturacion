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

    public function selectBuscaClientes(array $d){
        $sel = "select id,nombre,cif,poblacion from clientes where id_usuario='$d[idusu]' and $d[campo] like '%$d[buscar]%' order by $d[campo]";
        $rsel = $this->db->query($sel);
        if($rsel){
            $r = [];
            for($i=0;$i<$rsel->num_rows;$i++){
                $r[] = $rsel->fetch_assoc();
            }
            $r['ok'] = 'si';
            $r['nl'] = $rsel->num_rows;
        }else{
            $r['ok'] = 'no';
            $r['oks'] = $sel;
        }

        return $r;
    }

    public function selectClienteId(int $id){

        $sel = "select id,id_usuario, cif, nombre, direccion, poblacion, cp, provincia, pais, web, email, obser, descuento, forma_pago, movil, telf, date_format(DATE(fecha_alta), '%d/%m/%Y') as fecha_alta, contacto, activo from clientes where id='$id'";
        $rsel = $this->db->query($sel);
        if($rsel){
            $r = $rsel->fetch_assoc();
            $r['ok'] = 'si';
            $r['nl'] = $rsel->num_rows;
        }else{
            $r['ok'] = 'no';
            $r['oks'] = $sel;
        }

        return $r;
    }

    public function updateClienteId(array $d){
        $upd = "update clientes set nombre=?, direccion=?, poblacion=?, cp=?, provincia=?, pais=?, web=?, email=?, obser=?, descuento=?, forma_pago=?, movil=?, telf=?,  contacto=?, activo=? where id='$d[id]'";
        $stmt = $this->db->prepare($upd);
        $stmt->bind_param('sssssssssdsssss',$d['nombre'],$d['direccion'],$d['poblacion'],$d['cp'],$d['provincia'],$d['pais'],$d['web'],$d['email'],$d['obser'],$d['descuento'],$d['forma_pago'],$d['movil'],$d['telf'],$d['contacto'],$d['activo']);
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