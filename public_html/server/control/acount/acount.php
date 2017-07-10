<?php

require_once 'abcDB.php';

class Acount extends AbcDB
{
    
    private $data;
    
    /**
     * Nombre de la tabla
     * @var String
     */
    private $table = 'acount';
    /**
     * Campo identificador (nombre)
     * @var String
     */
    private $id = 'idAcount';
    /**
     * Campo descripcion
     * @var String
     */
    private $descrip = 'desccription';
    
    /**
     * Contructor padre
     * @param object $data
     */
    function __contruct($data)
    {
        parent::__contruct();//Contructor hijo
        $this->data = $data; // Asignacion a variable de clase
    }
    
    /**
     * Extraccion de todos los campos de la tabla padre
     */
    function remake()
    {
        $result = parent::select($this->table, '*', null);
        parent::closeConection();
        
        return $result;
    }
}
