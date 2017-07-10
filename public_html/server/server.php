<?php

$json = file_get_contents("php://input");
/** Objeto mandado mediante JSON */
$obj = json_decode($json);

if (isset($obj->class)) 
{
    if (isset($obj->method)) 
    {
        /** Variable de nombre de Clase */
        $class = $obj->class;
        
        /** Variable de nombre de Archivo .php */
        $file = lcfirst($class);
        
        /** Variable de ruta donde se encuentra el archivo PHP */
        $route = (isset($obj->location)) ? 
                "control/$obj->location/$file" . ".php" : 
                    "control/$file/$file" . ".php";
        
        /** Variable de metodo a lanzar creado el objeto */
        $method = $obj->method;
        
        require_once $route;

        /** Objeto de la clase X */
        $object = new $class($obj);
        unset($obj);
        
        /** Respuesta del metodo de clase X */
        $response = $object->$method();
        unset($object);
        
        echo json_encode($response);
    } 
    else 
    {
        echo "SIN METODO";
    }
} 
else 
{
    echo "SIN CLASE";
}