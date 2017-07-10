<?php

require_once 'abcDB.php';

class User extends AbcDB
{
    /**
     * Variable de entrada de datos:
     *      newUser():->data
     *          ->email
     *          ->name
     *          ->lastName
     *          ->phone
     * 
     * @var object
     */
    private $data;
    
    /**
     * Tabla padre de db
     * @var type 
     */
    private $tableP = 'user';
    //Columnas dentro de a tabla Padre
    private $email = 'email'; // Herencia dentro de las dos tablas
    private $name = 'name';
    private $lastName = 'lastName';
    private $phone = 'phone';
    
    /**
     * Tabla Hija de DB
     * @var type 
     */
    private $tableC = 'detail';
    //Columnas dentro de la tabla Hija
    private $user = 'user';
    private $pass = 'pass';
    private $idAcount = 'idAcount';
    
    /**
     * Constructor de clase
     * @param object $data
     */
    function __construct($data)
    {
        parent::__construct();
        $this->data = $data;
    }
    
    /**
     * Agregacion de un usuario en la base de datos
     * @return array
     */
    function newUser()
    {
        $result = parent::insert($this->tableP,$this->satz->assemble(
                array(
                    $this->email,
                    $this->name,
                    $this->lastName,
                    $this->phone
                ), false), 
                $this->satz->assemble(
                array(
                    $this->data->data->email,
                    $this->data->data->name,
                    $this->data->data->lastName,
                    $this->data->data->phone
                ), true));
        
        parent::closeConection();
        
        return array('status' => $result);
    }
    
    /**
     * Actualizacion de datos generales de usuario
     * @return array
     */
    function updateUser()
    {
        $result = parent::update($this->tableP, $this->satz->assmUpdate(
                array(
                    $this->name,
                    $this->lastName,
                    $this->phone
                ), 
                array(
                    $this->data->data->name,
                    $this->data->data->lastName,
                    $this->data->data->phone
                )), $this->email . ' like ' . $this->satz->apst($this->data->data->email));
        
        parent::closeConection();
        return array('status' => $result);
    }
    
    /**
     * Eliminacion de usuario
     * @return array
     */
    function deleteUser()
    {
        $status = parent::delete($this->tableP, $this->email . ' like ' . $this->satz->apst($this->data->email));
        
        parent::closeConection();
        
        return array('status' => $status);
    }
    
    /**
     * Extraer usuarios de la base de datos 
     */
    function remake()
    {
        $resultUser = parent::select($this->tableP, '*', null);
        
        for($i = 0; count($resultUser) > $i; $i++)
        {
            $tmp = parent::select($this->tableC, '*', $this->email . ' like ' . $this->satz->apst($resultUser[$i]['email']));
            array_push($resultUser[$i], $tmp);
        }
        
        parent::closeConection();
        return $resultUser;
    }
}