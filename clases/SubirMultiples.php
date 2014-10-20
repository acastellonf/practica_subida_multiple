<?php
/**
* Class SubirMultiples
*
* @version 1.0
* @author acastellon
* @license free
* @copyright izv by acastellon
* Esta clase dispone de métodos para validar y subir múltiples archivos 
* seleccionados en un formulario.
* 
*/
class SubirMultiples {
    private $files, $input, $destino, $nombre,$nombreActual,$extActual,
            $accion, $maximo, $tipos, $extensiones,$crearCarpeta;
    private $errorPHP, $error;

    const IGNORAR = 0, RENOMBRAR = 1, REEMPLAZAR = 2;
    const ERROR_INPUT = -1;

    function __construct($input) {
        $this->input = $input;
        $this->destino = "./";
        $this->nombre = "";
        $this->nombreActual = "";
        $this->extActual = "";
        $this->accion = SubirMultiples::IGNORAR;
        $this->maximo = 2 * 1024 * 1024;
        $this->crearCarpeta = false;
        $this->tipos = array();
        $this->extensiones = array();
        $this->errorPHP = UPLOAD_ERR_OK;
        $this->error = 0;
    }//
    
    function getErrorPHP() {
        return $this->errorPHP;
    }//

    function getError() {
        return $this->error;
    }//

    function getErrorMensaje(){
        
    }
    
    function setCrearCarpeta($crearCarpeta) {
        $this->crearCarpeta = $crearCarpeta;
    }

    function setDestino($destino) {
        $caracter = substr($destino, -1);
        if ($caracter != "/")
            $destino.="/";
        $this->destino = $destino;
    }//

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }//

    function setAccion($accion) {
        $this->accion = $accion;
    }//

    function setMaximo($maximo) {
        $this->maximo = $maximo;
    }//

    function addTipo($tipo) {
        if (is_array($tipo)) {
            $this->tipos = array_merge($this->tipos, $tipo);
        } else {
            $this->tipos[] = $tipo;
        }
    }//

    function setExtension($extension) {
        if (is_array($extension)) {
            $this->extensiones = $extension;
        } else {
            unset($this->extensiones);
            $this->extensiones[] = $extension;
        }
    }//
    
    function addExtension($extension) {
        if (is_array($extension)) {
            $this->extensiones = array_merge($this->extensiones, $extension);
        } else {
            $this->extensiones[] = $extension;
        }
    }//

    function isInput(){
        if (!isset($_FILES[$this->input])) {
            $this->error = -1;
            return false;
        }
        return true;
    }//
    
    private function isError(){
        if ($this->errorPHP != UPLOAD_ERR_OK) {
            return true;
        }
        return false;
    }//
    
    private function isTamano($archivo){
        if ($archivo["size"] > $this->maximo) {
            $this->error = -2;
            return false;
        }
        return true;
    }//

    private function isExtension($extension){
        if (sizeof($this->extensiones) > 0 && !in_array($extension, $this->extensiones)) {
            $this->error = -3;
            return false;
        }
        return true;
    }//
    
    private function isCarpeta(){
        if (!file_exists($this->destino) && !is_dir($this->destino)) {
            $this->error = -4;
            return false;
        }
        return true;
    }//
    
    private function crearCarpeta() {  
        return mkdir ( $this->destino , 777, true);      
    }
    
    private function rehacerArray($array) {
        $archivos = array();
        $file_count = count($array['name']);
        $file_keys = array_keys($array);

        for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                $archivos[$i][$key] = $array[$key][$i];
            }
        }
        return $archivos;
    }
    
    function subirArray(){
        $this->error = 0;
        if(!$this->isInput()){
            return false;
        }
        $resultado=array();
        $this->files = $this->rehacerArray($_FILES[$this->input]);
        $files_count=count($this->files);
        for($i=0; $i<$files_count; $i++){
            $this->subir($this->files[$i]);
            $resultado[$i]["nombre"]=$this->nombreActual.".".$this->extActual;
            $resultado[$i]["error"]=$this->error;
        }
        return $resultado;
    }
            
    private function subir($archivo) {
        $this->error = 0;
        $this->archivo = $archivo;
        $this->errorPHP = $this->archivo["error"];
        $partes = pathinfo($this->archivo["name"]);
        $this->extActual = $partes['extension'];
        $nombreOriginal = $partes['filename'];
        if ($this->nombre === "") {
            $this->nombreActual = $nombreOriginal;
        }else{
            $this->nombreActual = $this->nombre;
        }
        
        if($this->isError()){
            return false;
        }
        if(!$this->isTamano($this->archivo)){
            return false;
        }
        if(!$this->isCarpeta()){
            if($this->crearCarpeta){
                $this->error=0;//
                if(!$this->crearCarpeta()){
                    $this->error=-7;
                    return false;
                }       
            } else{
                return false;
            }
        }
        
        
        if(!$this->isExtension($this->extActual)){
            return false;
        }
        
        $origen = $this->archivo["tmp_name"];
        $destino = $this->destino . $this->nombreActual . "." . $this->extActual;
        if ($this->accion == SubirMultiples::REEMPLAZAR) {
            return move_uploaded_file($origen, $destino);
        } elseif ($this->accion == SubirMultiples::IGNORAR) {
            if (file_exists($destino)) {
                $this->error = -5;
                return false;
            }
            return move_uploaded_file($origen, $destino);
        } elseif ($this->accion == SubirMultiples::RENOMBRAR) {
            $i = 1;
            while (file_exists($destino)) {
                $destino = $destino = $this->destino . $this->nombreActual . "_$i." . $this->extActual;
                $i++;
            }
            return move_uploaded_file($origen, $destino);
        }
        $this->error = -6;
        return false;
    }
}
