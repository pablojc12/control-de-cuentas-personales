<?php

class Sanitizer
{
    /**
     * function apst($var){}
     * Recibe un parametro de tipo String or Number.
     * 
     * Datos de tipo String:
     *      Parsea ell dato y le coloca apostrofes por ambos lados 
     * 
     * Datos de tipo Number:
     *      Los deja del mismo tipo sin colocarle los apostrofes
     * 
     * 
     * @param String or Number $var
     * @return String or Number
     */
    function apst($var)
    {
        $tmp = null;
        
        if (is_string($var)){
            $tmp = "'$var'";
        } else{
            $tmp = $var;
        }
        
        return $tmp;
    }
    
    /**
     * function assemble($var){}
     * 
     * Recive un arreglo de tipo numerico
     * 
     * Ensamlba el arreglo de cadenas ingresado y parsea las mismas 
     * con la funcion de clase apst().
     * 
     * Para valores tipo cadena:
     *      Pone apostrofes en ambos extremos de cada cadena 
     * 
     * Para valores tipo numericos:
     *      Deja sin apostrofes quedando el valor de tipo numerico
     * 
     * @param array $var
     * @return string
     */
    function assemble($var, $apst)
    {
        $tmp = "";
        
        if (is_array($var))
        {
            $count = count($var);
            $tmpCount = 0;
            
            if ($count > 1) 
            {
                foreach ($var as $value) {
                    $tmpCount ++;
                    $tmp .= ($apst) ? $this->apst($value) : $value;
                    
                    if($tmpCount < $count){$tmp .= ", ";}
                }
                
                unset($value);
            }
            else{ exit("clase Sanitizer funcion assemble() -> "
                    . "el parametro ingresado solamente contiene un elemento en el arreglo. "
                    . "Se recomienda usar solamente la funcion apst() encontrada dentro de la misma clase");}
        }else{ exit("clase Sanitizer funcion assemble() -> "
                    . "El parametro de entrada no es un arreglo");}
        
        return $tmp;
    }
    
    /**
     * function assmUpdate ($col, $val)
     * 
     * Recibe dos arreglos numericos
     *      $col => columnas de la tabla en curso
     *      $val => contenido de las columnas de tabla en curso
     * 
     * Para evitar revolver datos  al ingresar los arreglos, ambos deben 
     * contar con el mismo numero de posiciones y la columna con su resectivo 
     * valor en la misma posicion: 
     *      EJ. 
     *          $col => array(col_1, col_2, col_3)
     *          $val => array(val_1, val_2, val_3)
     * 
     * @param array $col
     * @param array $val
     * @return string
     */
    function assmUpdate ($col, $val)
    {
        $tmp = "";
        
        if (is_array($val) && is_array($col))
        {
            $count = count($col);
            
            if ($count > 1) 
            {
                for($c = 0; $count > $c; $c++)
                {
                    $tmp .= $col[$c] . ' = ' . $this->apst($val[$c]);
                    
                    if($c < $count-1){$tmp .= ", ";}
                }
                
                unset($col);
                unset($val);
            }
            else{ exit("clase Sanitizer funcion assmUpdate() -> "
                    . "el parametro ingresado solamente contiene un elemento en el arreglo. "
                    . "Se recomienda usar solamente la funcion apst() encontrada dentro de la misma clase");}
        }else{ exit("clase Sanitizer funcion assmUpdate() -> "
                    . "El parametro de entrada no es un arreglo");}
        
        return $tmp;
    }
}
