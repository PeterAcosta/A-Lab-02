<?php
#-------------------------------------------------------------------------------------------------------------
# Class name    : c_lib
# Class created : lunes 23 de febrero de 2009 por Peter Acosta
# Class description : crea y maneja las librerias
#-------------------------------------------------------------------------------------------------------------

#1. __construct()
#2. __destruct()
#3.  cantidad()
#4.  go($clave,$goto='')
#5.  first_last()
#6.  actualiza()
#7.  actualiza_blocks_per_lib()
#8.  AddNew()
#9.  existe_en_folder($folder,$lib_key)
#10. blocks_in_lib($lib_key)
#11. lib_exists($lib_key)


class c_lib
{
    public $cant = 0;

    #--------------------------------------------------------------------------------------------------------------------------------------1
    # function   : __construct()
    # Created    :
    # Parameters :
    # Return     :
    #public function __construct(){
    #}
    #--------------------------------------------------------------------------------------------------------------------------------------2
    # function   : __destruct(){
    # Created    :
    # Parameters :
    # Return     :
    #public function __destruct(){
    #}


    #--------------------------------------------------------------------------------------------------------------------------------------3
    # function   : cantidad()
    # Created    : miercoles 25 de marzo de 2009 por Peter Acosta
    # Parameters : none
    # Variables  : INT $_SESSION['tablas']['lib']['cantidad']
    # Return     : INT con la cantidad de registros que existen en la tabla lib
    public function cantidad()
    {
        if ($this->cant == 0) {
            $resultado = mysqli_query($GLOBALS['link'], 'SELECT COUNT(*) AS cant FROM libs') or die('ERROR al intentar contar la tabla Libs  - ');
            $fila = mysqli_fetch_assoc($resultado);
            $this->cant = $fila['cant'];
        }
        return $this->cant;
    }


    #--------------------------------------------------------------------------------------------------------------------------------------4
    # function   : go($clave,$goto='')
    # Created    : miercoles 25 de marzo de 2009 por Peter Acosta
    # Parameters : INT $clave and/or 'first'  'previous'  'next'  'last'
    # Return     : ARRAy con los campos de la  libreria encontrada
    public function go($clave, $goto = '')
    {
        $goto = strtolower($goto);
        $hacer_querry = true;
        if ($clave > 0 and $goto == '') {
            $sql = 'SELECT * FROM libs WHERE lib_key =' . $clave;
        } elseif ($goto == 'first') {
            $sql = "SELECT * FROM libs ORDER BY lib_key LIMIT 1";
        } elseif ($goto == 'next') {
            $sql = "SELECT * FROM libs WHERE lib_key>$clave ORDER BY lib_key LIMIT 1";
        } elseif ($goto == 'previous') {
            $sql = "SELECT * FROM libs WHERE lib_key<$clave ORDER BY lib_key DESC LIMIT 1";
        } elseif ($goto == 'last') {
            $sql = "SELECT * FROM libs ORDER BY lib_key DESC LIMIT 1";
        } else {
            $hacer_querry = false;
        }
        if ($hacer_querry) {
            $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_lib->go( $clave ) - $sql " . mysqli_error($GLOBALS['link']));
            $fila = mysqli_fetch_assoc($resultado);
        } else {
            $fila = false;
        }
        if ($fila) {
            $fila['existe'] = true;
        } else {
            $fila['lib_key'] = $clave;
            $fila['existe'] = false;
        }
        return $fila;
    }


    #--------------------------------------------------------------------------------------------------------------------------------------5
    # function   : first_last()
    # Created    : miercoles 25 de marzo de 2009 por Peter Acosta
    # Parameters : none
    # Variables  : INT $_SESSION['tablas']['blocks']['first']   y  $_SESSION['tablas']['blocks']['last']
    # Return     : none
    public function first_last($cat1 = 0, $cat2 = 0)
    {
        $cat1 = intval($cat1);
        if ($cat1 > 0) {
            $cat2 = intval($cat2);
            if ($cat2 > 0) {
                $where = "WHERE cat1_key=$cat1 AND cat2_key=$cat2";
            } else {
                $where = "WHERE cat1_key=$cat1";
            }
        } else {
            $where = '';
        }
        $libs_pos = array('first' => 0, 'last' => 0);
        # First
        $sql1 = "SELECT lib_key FROM libs $where ORDER BY lib_key LIMIT 1";
        $resultado1 = mysqli_query($GLOBALS['link'], $sql1) or die("ERROR - c_lib->first_last() - $sql1 " . mysqli_error($GLOBALS['link']));
        $fila1 = mysqli_fetch_assoc($resultado1);
        $libs_pos['first'] = $fila1['lib_key'];
        #Last
        $sql2 = "SELECT lib_key FROM libs $where ORDER BY lib_key DESC LIMIT 1";
        $resultado2 = mysqli_query($GLOBALS['link'], $sql2) or die("ERROR - c_lib->first_last() - $sql2 " . mysqli_error($GLOBALS['link']));
        $fila2 = mysqli_fetch_assoc($resultado2);
        $libs_pos['last'] = $fila2['lib_key'];

        return $libs_pos;
    }




    #--------------------------------------------------------------------------------------------------------------------------------------6
    # function   : actualiza()
    # Created    : viernes 3 de abril de 2009 por Peter Acosta
    # Parameters : none
    # Return     : none ; actualiza las librerias
    function actualiza_days_sum()
    {
        $sql = 'UPDATE libs SET days_sum=DATEDIFF(CURRENT_DATE(),lib_day) , download_average=download_total/IF(days_sum>0,days_sum,1)';
        $nada = mysqli_query($GLOBALS['link'], $sql) or die('<br />ERROR MySQL : <br />' . $sql . '<br />' . '<br />' . mysqli_error($GLOBALS['link']));
        return;
    }


    #--------------------------------------------------------------------------------------------------------------------------------------7
    # function   : actualiza_blocks_per_lib()
    # Created    : Sabado 29 de Agosto de 2009 por Peter Acosta
    # Updatd     : Jueves 5 de Abril de 2018 por Peter Acosta
    # Parameters : none
    # Return     : none ; Cuenta cuantos bloques tiene cada libreria
    function actualiza_blocks_per_lib()
    {
        // $sql = "SELECT lib_key , COUNT(*) FROM blocks GROUP BY lib_key";
        $sql = 'SELECT lib_key , COUNT(*) FROM blocks WHERE lib_key > 0 GROUP BY lib_key;';
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - mycat1->actualiza_blocks_per_lib() - $sql <br />" . mysqli_error($GLOBALS['link']));
        while ($row = mysqli_fetch_assoc($resultado)) {
            $sql2 = "UPDATE libs SET cant_blocks={$row['COUNT(*)']} WHERE lib_key={$row['lib_key']}";
            $resultado2 = mysqli_query($GLOBALS['link'], $sql2) or die("<br />ERROR - mycat1->actualiza_blocks_per_lib() - $sql2 <br />" . mysqli_error($GLOBALS['link']));
        }
    }
    #--------------------------------------------------------------------------------------------------------------------------------------8
    # function   : AddNew()
    # Created    : sabado 4 de abril de 2009 por Peter Acosta
    # Parameters : none
    # Return     : INT con el key de la lib que se acaba de agregar
    public function AddNew()
    {
        $largo_minimo = 8; # "New Lib"
        $sql = "SELECT lib_key FROM libs WHERE LENGTH(lib_file)<$largo_minimo ORDER BY lib_key LIMIT 1";
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_libs->AddNew() 1 - $sql " . mysqli_error($GLOBALS['link']));
        $fila = mysqli_fetch_assoc($resultado);
        if ($fila) {
            # Ya hay un block vacio sin usar
            $new_key = $fila['lib_key'];
        } else {
            # Creo un nuevo bloque
            $new_file = 'New Lib';
            $user = $_SESSION['user']['key'];
            $dia = date("Ymd");
            $sql = 'INSERT INTO libs SET lib_file="' . $new_file . '"';
            $sql .= ', user_key=' . $user;
            $sql .= ', days_sum=1';
            $sql .= ', lib_day="' . $dia . '"';
            mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_libs->AddNew() 2 - $sql " . mysqli_error($GLOBALS['link']));
            $new_key = mysqli_insert_id($GLOBALS['link']);
            #acomodo otros parametros como : cantidad , primero y ultimo
            $_SESSION['tablas']['libs']['cantidad']++;
        }

        return $new_key;
    }
    #--------------------------------------------------------------------------------------------------------------------------------------9
    # function   : existe_en_folder($folder,$lib_key)
    # Created    : lunes 6 de abril de 2009 por Peter Acosta
    # Parameters : STRING con la ruta de una carpeta valida
    # Return     : ARRAY con todos los NOMBRES que encontro en esa carpeta que conincidan con el actual bloque
    # Formato    : Ceco.NET-People-Men-m-2.dwg.zip  o  Ceco.NET-People-Men-e-32.dxf.zip
    public function existe_en_folder($folder, $lib_key)
    {
        $current_folder = getcwd();
        chdir($folder);
        $result = glob('*-' . $lib_key . '.*');
        chdir($current_folder);
        return $result;
    }
    #-------------------------------------------------------------------------------------------------------------------------------------10
    # function   : blocks_in_lib($lib_key)
    # Created    : lunes 6 de abril de 2009 por Peter Acosta
    # Parameters : INT con el key de la libreria
    # Return     : ARRAY con la data de los bloques que pertenecen a esta libreria
    public function blocks_in_lib($lib_key)
    {
        $campos = '*';
        $sql = "SELECT $campos FROM blocks WHERE lib_key=$lib_key ";
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_libs->blocks_in_lib($lib_key) - $sql " . mysqli_error($GLOBALS['link']));
        $blocks = array();
        while ($aux = mysqli_fetch_assoc($resultado)) {
            $blocks[$aux['block_key']] = $aux;
        }
        mysqli_free_result($resultado);
        return $blocks;
    }


    /** ** ****************************************************************************************************************************** 11
     * @example lib_exists($lib_key)
     * @since Lunes 16 de abril de 2018
     * @author Peter Acosta
     * @param INT $lib_key ( la key de la libreria que se desea saber si existe o no
     * @return bool ( TRUE si la libreria existe o FALSE si la libreria no existe )
     */
    public function lib_exists($lib_key)
    {
        $sql = 'SELECT lib_key FROM libs WHERE lib_key=' . $lib_key;
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die('ERROR al intentar contar la tabla Libs  - ');
        $fila = mysqli_fetch_assoc($resultado);
        return ($fila['lib_key']) ? true : false;
    }


    public static function get($lib_key)
    {
        $resultado = mysqli_query($GLOBALS['link'], 'SELECT lib_key,lib_name,lib_url_alias,cat1_key,cat2_key,lib_title,lib_description,lib_keywords FROM libs WHERE lib_key=' . $lib_key) or die('ERROR get Lib');
        return mysqli_fetch_assoc($resultado);
    }


    /** ** ****************************************************************************************************************************** 12
     * @example c_lib::get_url_alias($lib_key,$full)
     * @since Sabado 12 de Mayo de 2018
     * @author Peter Acosta
     * @param int $lib_key
     * @param int $full ( 0 = solo lo que se guarda en la db , 1 = param , 2 = URL completa )
     * @return string lib_url_alias
     */
    public static function get_url_alias($lib_key, $full = 0)
    {
        $result = mysqli_query($GLOBALS['link'], 'SELECT lib_url_alias FROM libs WHERE lib_key=' . $lib_key) or die('ERROR al intentar obtener lib_url_alias');
        $fila = mysqli_fetch_assoc($result);
        return c_lib::aux_url_alias($lib_key, $fila['lib_url_alias'], $full);
    }


    /** ** ****************************************************************************************************************************** 13
     * @example c_lib::aux_url_alias($lib_key, $lib_url_alias, $full)
     * @since miercoles 6 de junio de 2018
     *
     * @param $lib_key
     * @param $lib_url_alias
     * @param int $full ( 0 = solo lo que se guarda en la db , 1 = param , 2 = URL completa )
     * @return string $lib_url_alias
     */
    public static function aux_url_alias($lib_key, $lib_url_alias, $full = 0)
    {
        if ($full > 0) {
            $lib_url_alias = CECO_URL_ALIAS_BASE_LIBRARY . '/' . $lib_url_alias . CECO_URL_ALIAS_FINAL_LIBRARY . $lib_key;
            if ($full == 2) {
                $lib_url_alias = CECO_URL_DOMINIO . '/' . $lib_url_alias;
            }
        }
        return $lib_url_alias;
    }


}
