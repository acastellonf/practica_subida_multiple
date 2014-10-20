<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Subir múltiples archivos</title>
        <link rel="stylesheet" type="text/css" href="css/estilos.css">
    </head>
    <body>
        <div class="contenedor">
            <form  class="formulario" enctype="multipart/form-data" method="post" action="subida.php">
                <h3>Subida múltiple</h3>
                <div class="linea">
                    <p><label  for="nombre">Nombre archivos: </label></p>
                    <p><input type="text"  name="nombre" /> </p>
                </div>
                <div class="linea">
                    <p><label for="archivo[]">Seleccione archivos a subir* </label></p>
                    <p><input type="file"  name="archivo[]" multiple/> </p>
                </div>
                <div class="linea">
                    <p><label>Indica modo </label></p>
                    <p><input id="renombrar" type="radio"  name="modo" value="rename"/>
                        <label for="renombrar">Renombrar</label></p>
                    <p><input id="reemplazar" type="radio"  name="modo" value="reemplazar"/>
                        <label for="reemplazar">Reemplazar</label></p>
                </div>
                <p class="aviso">*Solo pueden subirse archivos con extensiones ".txt" y ".pdf"</p>
                <p class="aviso">*Tamaño máximo del archivo 100KB</p>
                <div class="linea">
                        <input type="submit" name="submit" value="Enviar" />
                </div>
            </form>
        
            <div id="listado">
                <h4>Listado directorio</h4>
                <?php
                    $dir = 'prueba';
                    if(file_exists($dir)){
                        $files = scandir($dir);
                        for($i=2;$i<count($files);$i++){
                            echo '<p><img src="css/icono.png" />'.$files[$i]."</p>";
                        }
                    }
                ?>
            </div>
            
        </div>
        <?php
            require_once './require/comun.php';

            $resultado=Leer::get("array");
            if($resultado!=null){
                $tmp = stripslashes($resultado); 
                $tmp = urldecode($tmp); 
                $resultado = unserialize($tmp); 

                echo '<div id="resultado"><h4>Resultado de la subida</h4>';

                for($i=0;$i<count($resultado);$i++){
                    echo "<p>Archivo: ".$resultado[$i]["nombre"]." > ";
                    if($resultado[$i]["error"]===0){
                        echo "<i>Subido correctamente</i></p>";
                    }else{
                        echo "<i>No subido. Error: ".$resultado[$i]["error"]."</i></p>";
                    }
                }
                echo '</div>';
            }    
        ?>
    </body>
</html>
