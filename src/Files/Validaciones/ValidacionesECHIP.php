<?php


namespace Files\Validaciones;


//use Facturacion\DAO\ClientesDAO;

class ValidacionesECHIP {

    private $r = array();
    /*private $clientesDAO;

    public function __construct(ClientesDAO $empresasDAO) {
        $this->empresasDAO = $empresasDAO;
    }*/

    public function control_inputs(array $v){
        $patron['nombres'] = "/^[a-zñáéíóúÑÁÉÍÓÚ\-\/ ]{3,}$/i";
        $patron['nombres-b'] = "/^(|[a-zñáéíóúÑÁÉÍÓÚ\-\/ ]{3,})$/i";
        $patron['ciudad'] = "/^[a-zñáéíóúÑÁÉÍÓÚ()\/\- ]{3,}$/i";
        $patron['direc'] = "/^([a-z0-9ñáéíóúÑÁÉÍÓÚºª#:\/\,\.\- ]{3,})$/i";
        $patron['direc-b'] = "/^(|[a-z0-9ñáéíóúÑÁÉÍÓÚºª#:\/\,\.\- ]{3,})$/i";
        $patron['dni'] = "/^([a-z]{1}[0-9]+|[0-9]+[a-z]{1}|[a-z]{1}[0-9]+[a-z]{1})$/i";
        $patron['dni-b'] = "/^(|[a-z]{1}[0-9]+|[0-9]+[a-z]{1}|[a-z]{1}[0-9]+[a-z]{1})$/i";
        $patron['cp'] = "/^[0-9]{2,7}$/i";
        $patron['combo'] = "/^[a-z]{2,7}$/i";
        $patron['cp-b'] = "/^(|[0-9]{4,7})$/i";
        $patron['clave'] = "/^[0-9]{5,15}$/";
        $patron['codcont'] = "/^[a-z0-9\-]{1,5}$/i";
        $patron['sexo'] = "/^(hombre|mujer){1}$/i";
        $patron['pass'] = "/^\\S{5,15}$/";
        $patron['email'] = "/^[a-zA-Z0-9\.\-\_]+@[a-zA-Z0-9\.\-\_]+[\.][a-zA-Z]{2,3}$/";
        $patron['email-b'] = "/^(|[a-zA-Z0-9\.\-\_]+@[a-zA-Z0-9\.\-\_]+[\.][a-zA-Z]{2,3})$/";
        $patron['empresas'] = "/^[a-z0-9ñáéíóúÑÁÉÍÓÚ&\.\- ]{3,}$/i";
        $patron['codigos'] = "/^[a-z0-9\-]+$/i";
        $patron['codigos-b'] = "/^(|[a-z0-9\-]+)$/i";
        $patron['numpedido'] = "/^[a-z0-9\-]{5,}$/i";
        $patron['telf'] = "/^[0-9\+]{5,15}$/i";
        $patron['telf-b'] = "/^(|[0-9\+]{5,15})$/i";
        $patron['ayun'] = "/^[0-5]{1}$/i";
        $patron['web'] = "/^(http|https){1}(:\/\/){1}([a-z0-9&@#\/%?=\-\_\:\/\.])+$/i";
        $patron['texto'] = "/^[a-z0-9ñáéíóúÑÁÉÍÓÚºª#:\/\,¿\?\.\-\\r\\n ]{3,}$/i";
        $patron['texto-b'] = "/^(|[a-z0-9ñáéíóúÑÁÉÍÓÚºª#:\/\,¿\?\.\-\\r\\n ]{3,})$/i";
        $patron['numero'] = "/^[0-9]{1,}$/";
        $patron['numero-b'] = "/^(|[0-9]{1,})$/";
        $patron['decimal'] = "/^[0-9\.]{3,}$/";
        $patron['decimal-b'] = "/^(|[0-9\.]{3,})$/";
        $patron['float2d'] = "/^([0-9])+(\.?[0-9]{2})$/";
        $patron['float'] = "/^([0-9])+(\.?[0-9]{0,})$/";
        $patron['float0d'] = "/^([0-9]{1,})$/";
        $patron['codcuenta'] = "/^([0-9]{2,5}|[0-9]{8})$/";
        $patron['fechamysql'] = "/^([0-9]{2,4}-[0-9]{1,2}-[0-9]{1,2})$/";


         switch($v['tipo']){
                case 'noval':
                        continue;      //Para que no valide poner tipo = noval
                    break;
                case 'fecha':
                    $r = [];
                   if(!preg_match($patron['fechamysql'], $v['value'])){
                        $this->r['ok'] = 'no';
                        $this->r['id'] = $v['id'];
                       return $this->r;
                        break ;
                    }
                    $sn = $this->control_fecha($v['value']);
                    if(!$sn){
                        $this->r['ok'] = 'no';
                        $this->r['id'] = $v['id'];
                        return $this->r;
                        break;
                    }else{
                        $this->r['ok'] = 'si';
                        return $this->r;
                    }

                    break;
                case 'fecha-b':
                    $r = [];
                    if(strlen($v['value']) < 1){
                        continue;
                        break;
                    }else{
                        $sn = $this->control_fecha($v['value']);
                        if(!$sn){
                            $this->r['ok'] = 'no';
                            $this->r['id'] = $v['id'];
                            return $this->r;
                            //break 2;
                        }else{
                            $this->r['ok'] = 'si';
                            return $this->r;
                        }
                    }

                    break;
                default:

                    $r = [];
                    if(!preg_match($patron[$v['tipo']], $v['value'])){
                        $this->r['ok'] = 'no';
                        $this->r['id'] = $v['id'];
                        $this->r['valor'] = $v['value'];
                        return $this->r;
                    }else{
                        $this->r['ok'] = 'si';
                        return $this->r;
                    }
                    break;
            }
    }

    public function control_fecha($fecha){
        if(strlen($fecha) == 10){
            $fe = explode("-",$fecha);
            if($fe[0] < 1900){
                return false;
            }else{
                return checkdate($fe[1],$fe[2],$fe[0]);
            }

        }


    }
}