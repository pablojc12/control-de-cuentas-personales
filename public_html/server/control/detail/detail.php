<?php

require_once 'abcDB.php';

class Detail extends AbcDB
{
    private $data;
    /**
     * tabla principal de acciones a realizar
     * @var String
     */
    private $table = 'detail';
    /**
     * Identificador de la tabla
     * @var String
     */
    private $id = 'idDetail'; 
    /**
     * Campo Usuario
     * @var String
     */
    private $user = 'user';
    /**
     * Campo Contraseña
     * @var String
     */
    private $pass = 'pass';
    /**
     * Campo Identificador de cuenta
     * @var String
     */
    private $acount = 'idAcount';
    /**
     * Campo idetificador de Usuario
     * @var String
     */
    private $email = 'email';
    
    /**
     * Contructor Hijo
     * @param object $data
     */
    function __construct($data)
    {
        parent::__construct();// Contructor padre
        $this->data = $data;
    }
    
    /**
     * Agregar cuentas al usuario seleccionado dependiendo de su email
     * @return array
     */
    function addDetail()
    {
        $result = false;
        $colums = $this->satz->assemble( array(
            $this->user,
            $this->pass,
            $this->acount,
            $this->email
        ),false);
        
        for($i = 0; count($this->data->acounts) > $i; $i++)
        {
            $tmpVal = $this->satz->assemble(array(
                $this->data->acounts[$i]->user,
                $this->data->acounts[$i]->pass,
                $this->data->acounts[$i]->acount,
                $this->data->email
            ),true);
            
            $result = parent::insert($this->table, $colums, $tmpVal);
        }
        
        return array('status' => $result);
    }
    
    /**
     * 
     * @return array
     */
    function deleteDetail()
    {
        $result = parent::delete($this->table, $this->id . '=' . $this->data->id);
        parent::closeConection();
        
        return array('status' => $result);
    }
}
