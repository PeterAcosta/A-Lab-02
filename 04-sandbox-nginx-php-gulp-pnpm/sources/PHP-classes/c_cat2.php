<?php

#-------------------------------------------------------------------------------------------------------------
# Class name    : c_categories2
# Class created : viernes 21 de febrero de 2009 por Peter Acosta
# Class autor   : Peter Acosta
# Formato : $_SESSION['cat2'][ $cat1_key ] [ $cat2_key ] ['url_alias']

#-------------------------------------------------------------------------------------------------------------
#1. __construct()
#2. __destruct()
#3. actualiza()
#4. addNew($cat1_key)
#5.

class c_cat2
{


    #---------------------------------------------------------------------------------------------------------1
    # function   : __construct()
    # Created    : viernes 21 de febrero de 2009 por Peter Acosta
    # Parameters : $link a la base de datos
    # Return     : none , vuelca los datos d ela tabla Categoria1 a variables de SESSION
    public function __construct()
    {

        /*
        if (!isset($_SESSION['cat2'])) {
            $t0 = microtime(true);
            $_SESSION['cat2'] = array();
            $sql = 'SELECT cat2_key , cat2_name , cat2_url_alias , cat1_key FROM cat2 ORDER BY cat2_name';
            $resultado = mysqli_query($link, $sql) or exit('<br />ERROR - No puedo leer cat2' . mysqli_error($link));
            while ($row = mysqli_fetch_assoc($resultado)) {

                $_SESSION['cat2'] [$row['cat1_key']] [$row['cat2_key']] =array('name'=>$row['cat2_name'],'url_alias'=>$row['cat2_url_alias']);

                // $_SESSION['cat2'] [$row['cat1_key']] [$row['cat2_key']] ['name'] = $row['cat2_name'];
                // $_SESSION['cat2'] [$row['cat1_key']] [$row['cat2_key']] ['keywords'] = $row['cat2_keywords'];
                // $_SESSION['cat2'] [$row['cat1_key']] [$row['cat2_key']] ['url_alias'] = $row['cat2_url_alias'];
            }
            $_SESSION['tablas']['cat2']['cantidad'] = mysqli_num_rows($resultado);
            mysqli_free_result($resultado);
            $_SESSION['tablas']['cat2']['time'] = round(microtime(true) - $t0, 4);
        }
        */


    }


    #---------------------------------------------------------------------------------------------------------2
    #public function __destruct(){
    #echo "Esta es la funcion __destruct() de la clase c_categories2 <br />";
    #}
    #---------------------------------------------------------------------------------------------------------3
    # function   : actualiza()
    # Created    : Sabado 29 de agosto de 2009 por Peter Acosta
    # Parameters : none
    # Return     : none ; cuenta la cantidad de blocuqes que pertenecen a cada cat1 y pasa valores
    function actualiza()
    {
        $sql = "SELECT cat2_key , COUNT(*) , SUM(view_total) , SUM(download_total) FROM blocks GROUP BY cat2_key";
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - mycat2->actualiza() - $sql <br />" . mysqli_error($GLOBALS['link']));
        while ($row = mysqli_fetch_assoc($resultado)) {
            $sql2 = "UPDATE cat2 SET cat2_total={$row['COUNT(*)']} , cat2_view={$row['SUM(view_total)']} , cat2_download={$row['SUM(download_total)']} WHERE cat2_key={$row['cat2_key']}";
            $resultado2 = mysqli_query($GLOBALS['link'], $sql2) or die("<br />ERROR - mycat2->actualiza() - $sql2 <br />" . mysqli_error($GLOBALS['link']));
        }
    }


    #---------------------------------------------------------------------------------------------------------4
    # function   : AddNew()
    # Created    : Domingo 15 de marzo de 2009
    # Parameters : INT la clave de la categoria 1 a la que pertenecera esta categoria 2
    # Return     : INT el KEY del nuevo registro o de algun registro soin usar
    function addNew($cat1_key)
    {
        $palabra_nuevo = 'new';
        $sql = 'SELECT cat2_key FROM cat2 WHERE cat2_name="' . $palabra_nuevo . '" ORDER BY cat2_key LIMIT 1';
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat2->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
        $fila = mysqli_fetch_assoc($resultado);
        $new_key2=0;
        if ($fila) {
            # Ya hay un registro vacio sin usar le cambio la categoria 1
            $new_key2 = $fila['cat2_key'];
            $sql = "UPDATE cat2 SET cat1_key=$cat1_key WHERE cat2_key=$new_key2";
            $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat2->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
        } else {
            # Creo un nuevo registro
            $sql = 'INSERT INTO cat2 SET cat2_name="' . $palabra_nuevo . '"';
            $sql .= ',cat1_key=' . $cat1_key;
            $sql .= ',cat2_day="' . date("Ymd") . '"';
            mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat2->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
            $new_key2 = mysqli_insert_id($GLOBALS['link']);
        }

        return $new_key2;
    }


    /** ** ******************************************************************************************************************************* 5
     * @example c_cat2::get_more_data($cat2_key)
     * Obtiene los campos cat2_title ,cat2_description , cat2_keywords
     * @param $cat2_key
     * @return array
     */
    public static function get_more_data($cat1_key,$cat2_key)
    {
        global $link, $CATs;
        $sql = 'SELECT cat2_title ,cat2_description , cat2_keywords  FROM cat2 WHERE cat2_key=' . $cat2_key;
        $resultado = mysqli_query($link, $sql) or die("ERROR - ($cat2_key) - $sql ");
        // $fila = mysqli_fetch_assoc($resultado);
        return array_merge($CATs[$cat1_key]['cat2'][$cat2_key], mysqli_fetch_assoc($resultado));
    }


    /** ** ******************************************************************************************************************************* 6
     * @example c_cat2::get_cat2()
     * @since viernes 25 de Mayo de 2018
     * @return array $cat2
     */
    public static function get_cat2() : array
    {
        global $link;
        $cat2=array();
        $sql = 'SELECT cat1_key , cat2_key , cat2_name , cat2_url_alias FROM cat2 ORDER BY cat2_name';
        $resultado = mysqli_query($link, $sql) or exit('<br />ERROR - No puedo leer cat2' . mysqli_error($link));
        while ($row = mysqli_fetch_assoc($resultado)) {
            $cat2 [$row['cat1_key']] [$row['cat2_key']] = array('name' => $row['cat2_name'], 'url_alias' => $row['cat2_url_alias']);
        }
        mysqli_free_result($resultado);
        return $cat2;
    }


}
