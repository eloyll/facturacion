<?php


namespace Facturacion\Model;


use Facturacion\DAO\UsuariosDAO;
use Files\Sesiones\GestionSesion;

class Usuarios {

    private $usuariosDAO;
    private $gestionsesion;

    public function __construct(UsuariosDAO $usuariosDAO, GestionSesion $gestionSesion) {

        $this->usuariosDAO = $usuariosDAO;
        $this->gestionsesion = $gestionSesion;
    }

    public function anadirUsuarios(){

    }

    public function usuario(array $datos){

        $d = $this->usuariosDAO->getUsuario($datos['dni']);
        $posi = [];
        if($d['nl'] != 1){
            $this->gestionsesion->setKey('FAC-PREOK','falso');
            for($i=0;$i<=2;$i++){
                $nu = rand(0,5);
                while(in_array($nu,$posi)){
                    $nu = rand(0,5);
                }
                $posi[$i] = $nu;
            }
            sort($posi);
        }else{
            $this->gestionsesion->setKey('FAC-USUARIO',$d['usuario']);
            $this->gestionsesion->setKey('FAC-DNI',$d['dni']);
            $this->gestionsesion->setKey('FAC-IDUSU',$d['id']);
            $this->gestionsesion->setKey('FAC-NIVEL',$d['nivel_aut']);
            $this->gestionsesion->setKey('FAC-PREOK','true');
            $clex = str_split($d['clave']);

            for($i=0;$i<=2;$i++){
                $nu = rand(0,5);
                while(in_array($nu,$posi)){
                    $nu = rand(0,5);
                }
                $posi[$i] = $nu;
            }
            sort($posi);
            $ncl = $clex[$posi[0]].$clex[$posi[1]].$clex[$posi[2]];
            $this->gestionsesion->setKey('FAC-NCL', $ncl);
            $this->gestionsesion->setKey('FAC-MD5NCL',md5($d['usuario'].$d['dni'].$ncl));
            $this->gestionsesion->setKey('FAC-POSI',$posi[0].$posi[1].$posi[2]);
        }


        return $posi;
    }

    public function login(array $d){

        if($this->gestionsesion->getKey('FAC-PREOK') == 'falso'){
            $r['ok'] = 'no';
            $r['msg'] = "Comprobar los datos";
            $this->gestionsesion->borraSesion();


        }else {

            $md5usu = md5($d['usuario'] . $d['dni'] . $d['clave']);
            if ($md5usu == $this->gestionsesion->getKey('FAC-MD5NCL')) {
                $r['ok'] = 'si';
            } else {
                $r['ok'] = 'no';
                $r['msg'] = 'Hay datos erróneos';
                //$this->gestionsesion->borraSesion();//unset($_SESSION);
            }
        }

        return $r;
    }

    public function valorKeySesion($key){

        return $this->gestionsesion->getKey($key);
    }

    public function usuarioBorraSesion(){

        return $this->gestionsesion->borraSesion();
    }
}