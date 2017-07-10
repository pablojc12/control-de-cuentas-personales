<?php
require_once 'configConection.php';
require_once 'sanitizer.php';

class AbcDB
{
    /** Objeto de MySQLi */
    private $db;
    /** Objecto de Sanitizador */
    public $satz;
    
    /**
     * Constructor de la clase
     */
    function __construct() 
    {
        $this->satz = new Sanitizer(); // Objeto de clase Sanitizador
        $mysqli = new mysqli(server, userDB, passDB, db); // Objeto de clase mysqli
        
        if ($mysqli->connect_errno) {
            exit($mysqli->connect_error); // Interrupcion de secuencia si es que existe un error en el objeto mysqli de coneccion
        } else {
            $this->db = $mysqli; // Asignacion del objeto mysqli al objeto de clase Base
            unset($mysqli);
        }
    }
    
    /**
     * protected function insert($table, $fields, $values)
     * 
     * parse $table = Nombre de la tabla en curso -> "Table"
     * parse $column = Columnas de la insercion -> "colum1, colum2, etc"
     * parse $values = valores de las columnas -> "'value1', 'value2', etc"
     * 
     * Para que la insercion de complete con exito se tienen que 
     * poner en el orden correcto la colum1 al 'value1' y susecibos
     * 
     * Retorna true en caso de exito, de lo contrario retorna false
     * 
     * @param String $table
     * @param String $column
     * @param Sstring $values
     * @return boolean
     */
    protected function insert($table, $column, $values)
    {
        $query = "INSERT INTO $table($column) VALUES($values)";
        
        $result = $this->db->query($query);
        
        if ($result === TRUE) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * protected function update($table, $values, $where)
     * 
     * parse $table = tabla del UPDATE en curso -> "Table"
     * parse $values = campos con valores -> "column1 = 'value1', column2 = 'value2', etc"
     * parse $where = where del UPDATE -> "columnPK (like or =) ('string' or number)"
     * 
     * La sintaxis para el where varia dependiendo si es string o numero 
     * para lo cual tenemos la siguiente secuencia 
     *  | like |->| 'string' |
     *  |   =  |->|  number  |
     * 
     * Retorna true en caso de exito, de lo contrario retorna false
     * 
     * @param String $table
     * @param String $values
     * @param String $where
     * @return boolean
     */
    protected function update($table, $values, $where)
    {
        $query = "UPDATE $table SET $values WHERE $where";
        $result = $this->db->query($query);
        
        if($result === TRUE){
            return true;
        }  else {
            return false;
        }
    }
    
    /**
     * protected function delete($table, $where)
     * 
     * parse $table = tabla para el DELETE en curso -> "Table"
     * parse $where = restriccion de DELETE -> "columnPK (like or =) ('string' or number)"
     * 
     * La sintaxis para el where varia dependiendo si es string o numero 
     * para lo cual tenemos la siguiente secuencia 
     *  | like |->| 'string' |
     *  |   =  |->|  number  |
     * 
     * Retorna true en caso de exito, de lo contrario retorna false
     * 
     * @param String $table
     * @param String $where
     * @return boolean
     */
    protected function delete($table, $where)
    {
        $query = "DELETE from $table WHERE $where";
        $result = $this->db->query($query);
        
        if($result === TRUE){
            return true;
        }
        else{
            return false;
        }
    }
    
    /**
     * protected function select($table, $colums, $where)
     * 
     * parse $table = tabla para el SELECT en curso -> "Table"
     * parse $colums = columnas de la extraccion -> "column1, column2, etc (or) * "
     *      El * sirve como comodin para todas las tablas
     * Campo Opcional
     *      parse $where = restriccion de la seleccion -> "columnPK (like or =) ('string' or number)"
     * 
     * La sintaxis para el where varia dependiendo si es string o numero 
     * para lo cual tenemos la siguiente secuencia 
     *  | like |->| 'string' |
     *  |   =  |->|  number  |
     * 
     * En caso de no tener where extraera todos los valores de las 
     * columnas seleccionadas 
     *      Ej. Con WHERE -> "SELECT $colums from $table WHERE $where"
     *      Ej. Sin WHERE -> "SELECT $colums from $table"
     * 
     * @param String $table
     * @param String $colums
     * @param String $where -> CAMPO OPCIONAL
     * @return array numerico
     */
    protected function select($table, $colums, $where)
    {
        $query = "";
        $data = [];
        
        if (isset($where)){
            $query = "SELECT $colums from $table WHERE $where";
        }else{
            $query = "SELECT $colums from $table";
        }
        
        $result = $this->db->query($query);
        
        if(!is_bool($result))
        {
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $data[] = $row;
                }
                $result->free();
            }
        }

        return $data;
    }
    
    /**
     * Cierre de coneccion y eliminacion de objetos db y satz
     */
    protected function closeConection()
    {
        $this->db->close();
        unset($this->db);
        unset($this->satz);
    }
}