<?php
/**
* ClassNombreClase
*
* @version 0.9
* @author izv
* @license http://...
* @copyright izv by cv
* Esta clase dispone de métodos estáticos 
* que se utilizan para la lectura de parámetros de entrada a través de get y post.
* 
*/
class Leer {
    
    /**
    * Trata de leer el parámetro de entrada que se pasa como argumento.
    * @access public
    * @param string $param cadena con el nombre del parámetro.
    * @return string | array | null Devuelve una cadena con el valor del parámetro...
    */
    public static function get($param){
        if(isset($_GET[$param])){
            $v = $_GET[$param];
            //comprobar si es valor o array
            if(is_array($v)){
                return Leer::leerArray($v);
            }else{
                return Leer::limpiar($v);;
            }
        }else{
            return null;
        }
        return ;
    }
    
    private static function leerArray($param){
        $array = array();
        foreach ($v as $key => $value){
            $array[]=Leer::limpiar($value);
        }
        return $array;
    }
    
    public static function isArray($param){
        if(isset($_GET[$param])){
            return is_array($_GET[$param]);
        }elseif (isset($_POST[$param])){
            return is_array($_POST[$param]);
        }
        return null;
    }
    
    public static function post($param){
        if(isset($_POST[$param])){
            $v = $_POST[$param];
            //comprobar si es valor o array
            if(is_array($v)){
                return Leer::leerArray($v);
            }else{
                return Leer::limpiar($v);;
            }
        }else{
            return null;
        }
        return ;
    }
    
    public static function request($param){
        $v = Leer::get($param);
        if($v==null){
            $v= Leer::post($param);
        }
        return $v;
    }
    
    
    private static function limpiar($param){
        return $param;
    }
    
    
}
