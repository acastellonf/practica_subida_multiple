<?php
        require_once './require/comun.php';

        $nombre=Leer::post("nombre");
        $modo=Leer::post("modo");
        
        if($modo==='rename'){
            $accion= SubirMultiples::RENOMBRAR;
        }elseif($modo==='reeemplazar'){
            $accion= SubirMultiples::REEMPLAZAR;
        }else{
            $accion=SubirMultiples::IGNORAR;
        }
        
        $maximo=100*1024;
        
        $subida=new SubirMultiples("archivo");
        $subida->setDestino("prueba");
        $subida->setCrearCarpeta(true);
        $subida->setAccion($accion);
        $subida->setMaximo($maximo);
        $subida->setExtension(array("txt","pdf"));
        $subida->setNombre($nombre);
        $resultado=$subida->subirArray();
        
        $serializado = serialize($resultado); 
        $resultado = urlencode($serializado); 
        header("Location:index.php?array=$resultado");
?>