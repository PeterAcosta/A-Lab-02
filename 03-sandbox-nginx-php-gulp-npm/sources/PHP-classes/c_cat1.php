<?php

#-------------------------------------------------------------------------------------------------------------
# Class name    : c_cat1
# Class created : viernes 21 de febrero de 2009 por Peter Acosta
# Class autor   : Peter Acosta
# Class description : categorias1
#-------------------------------------------------------------------------------------------------------------
#1. __construct()
#2. __destruct()
#3. actualiza()
#4. addNew()
#5. get_cat1_keywords($cat1_key)


class c_cat1
{

    #---------------------------------------------------------------------------------------------------------1
    # function   : __construct()
    # Created    : viernes 21 de febrero de 2009 por Peter Acosta
    # Parameters : $link a la base de datos
    # Return     : none , vuelca los datos de la tabla Categoria1 a variables de SESSION
    public function __construct()
    {
        /*
        if (!isset($_SESSION['cat1'])) {
            $t0 = microtime(true);
            $_SESSION['cat1'] = array();
            $resultado = mysqli_query($link, 'SELECT cat1_key,cat1_name,cat1_url_alias FROM cat1 ORDER BY cat1_name') or exit('<br />ERROR - No puedo leer cat1' . mysqli_error($link));
            while ($row = mysqli_fetch_assoc($resultado)) {
                $_SESSION['cat1'][$row['cat1_key']] = array('name' => $row['cat1_name'], 'url_alias' => $row['cat1_url_alias']);
            }
            $_SESSION['tablas']['cat1']['cantidad'] = mysqli_num_rows($resultado);
            mysqli_free_result($resultado);
            $_SESSION['tablas']['cat1']['time'] = round(microtime(true) - $t0, 4);
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
    public function actualiza()
    {
        $sql = "SELECT cat1_key , COUNT(*) , SUM(view_total) , SUM(download_total) FROM blocks GROUP BY cat1_key";
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - mycat1->actualiza() - $sql <br />" . mysqli_error($GLOBALS['link']));
        while ($row = mysqli_fetch_assoc($resultado)) {
            #echo "<br/>Cat1_key : {$row['cat1_key']} , {$row['COUNT(*)']} , {$row['SUM(view_total)']}<br/>";
            $sql2 = "UPDATE cat1 SET cat1_total={$row['COUNT(*)']} , cat1_view={$row['SUM(view_total)']} , cat1_download={$row['SUM(download_total)']} WHERE cat1_key={$row['cat1_key']}";
            $resultado2 = mysqli_query($GLOBALS['link'], $sql2) or die("<br />ERROR - mycat1->actualiza() - $sql2 <br />" . mysqli_error($GLOBALS['link']));
        }
    }


    #---------------------------------------------------------------------------------------------------------
    #---------------------------------------------------------------------------------------------------------4
    # function   : AddNew()
    # Created    : Domingo 15 de marzo de 2009
    # Parameters : none
    # Return     : INT el KEY del nuevo registro o de algun registro soin usar
    public function addNew()
    {
        $palabra_nuevo = 'NEW';
        $sql = 'SELECT cat1_key FROM cat1 WHERE cat1_name="' . $palabra_nuevo . '" ORDER BY cat1_key LIMIT 1';
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat1->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
        $fila = mysqli_fetch_array($resultado);
        if ($fila) {
            # Ya hay un registro vacio sin usar
            $new_key1 = $fila['cat1_key'];
        } else {
            # Creo un nuevo registro
            $sql = 'INSERT INTO cat1 SET cat1_name="' . $palabra_nuevo . '"';
            $sql .= ',cat1_day="' . date("Ymd") . '"';
            mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat1->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
            $new_key1 = mysqli_insert_id($GLOBALS['link']);
        }

        return $new_key1;
    }


    #---------------------------------------------------------------------------------------------------------


    /** ** ******************************************************************************************************************************* 5
     * @example c_cat1::get_more_data($cat1_key)
     * Obtiene los campos cat1_title ,cat1_description , cat1_keywords
     * @param $cat1_key
     * @return array
     */
    public static function get_more_data($cat1_key)
    {
        global $link, $CATs;
        $sql = 'SELECT cat1_title ,cat1_description , cat1_keywords  FROM cat1 WHERE cat1_key=' . $cat1_key;
        $resultado = mysqli_query($link, $sql) or die("ERROR - c_cat1->get_cat1_keywords($cat1_key) - $sql ");
        // $fila = mysqli_fetch_assoc($resultado);
        return array_merge($CATs[$cat1_key], mysqli_fetch_assoc($resultado));
    }


    /** ** ******************************************************************************************************************************* 6
     * @example c_cat1::get_all($cached = true)
     * @since Martes 22 de mayo de 2018
     * Devuleve un array con la estructura de las CATS y cat2
     * @param bool $cached ( true = lo trae de un archivo de cache , false = lo lee de la base de datos )
     * @return array|bool $c
     */
    public static function get_cat1_cat2_basic($cached = true)
    {
        $token = 'cat1-cat2-get-all';
        if ($cached) {
            $t0 = microtime(true);
            $c = c_cacher::array_load($token);
            $t1 = microtime(true);
            $_SESSION['time']['11_cat_cache'] = round($t1 - $t0, 4);
        } else {
            $c = false;
        }
        if (!$c) {
            $t0 = microtime(true);
            global $link;
            $sql = 'SELECT t1.cat1_key , t1.cat1_name , t1.cat1_url_alias , t2.cat2_key , t2.cat2_name , t2.cat2_url_alias 
                FROM cat1 AS t1 LEFT JOIN cat2 AS t2 ON t1.cat1_key = t2.cat1_key
                ORDER BY t1.cat1_name , t2.cat2_name ';
            $result = mysqli_query($link, $sql) or exit('<br />ERROR - No puedo leer cat1' . mysqli_error($link));
            $c = array();
            while ($row = mysqli_fetch_assoc($result)) {
                $c[$row['cat1_key']]['name'] = $row['cat1_name'];
                $c[$row['cat1_key']]['url_alias'] = $row['cat1_url_alias'];
                // $c[$row['cat1_key']]['keywords'] = $row['cat1_keywords'];
                if (!is_null($row['cat2_key'])) {
                    $c[$row['cat1_key']]['cat2'][$row['cat2_key']] = array('name' => $row['cat2_name'], 'url_alias' => $row['cat2_url_alias']);
                }
            }
            $t1 = microtime(true);
            $_SESSION['time']['10_cat_db'] = round($t1 - $t0, 4);

            // $c = c_cat1::get_main_titles_img($c);

            // ----- MAIN TITLE IMG -------------------------------------------------------------------------------------------
            $c[6]['main_title'] = 'autocad-architecture-drawings-blocks-templates-dwg-dxf.png';   // architecture
            $c[3]['main_title'] = 'autocad-bathrooms-detail-drawings-blocks-templates-dwg-dxf.png'; // Bathrooms Detail
            $c[14]['main_title'] = 'autocad-decorative-elements-drawings-blocks-dwg-dxf.png';  // Decorative elements
            $c[13]['main_title'] = 'autocad-equipment-drawings-blocks-dwg-dxf.png';     // Equipment
            $c[13]['cat2'][10]['main_title'] = 'autocad-sports-gym-drawings-blocks-templates-dwg-dxf.png';  // Sports and Gym
            $c[7]['main_title'] = 'autocad-furniture-blocks-drawings-templates-dwg-dxf.png';  // Furniture
            $c[4]['main_title'] = 'autocad-landscaping-blocks-drawings-templates-dwg-dxf.png';  // Landscaping
            $c[4]['cat2'][9]['main_title'] = 'autocad-plants-brushes-drawings-blocks-templates-dwg-dxf.png';  // Plants Bushes
            $c[4]['cat2'][8]['main_title'] = 'autocad-trees-drawings-blocks-templates-dwg-dxf.png';           // trees-plants

            $c[1]['main_title'] = 'people-autocad-blocks-drawings-templates-dwg-dxf.png';    // People
            $c[1]['cat2'][1]['main_title'] = 'autocad-man-men-people-blocks-drawings-templates-dwg-dxf.png';    // Men
            $c[1]['cat2'][2]['main_title'] = 'autocad-women-woman-drawings-blocks-templates-dwg-dxf.png';       // Women
            $c[1]['cat2'][4]['main_title'] = 'autocad-kids-family-children-groups-of-persons-drawings-blocks-dwg-dxf.png';        // Children
            ## $c[1]['cat2'][12]['main_title'] = 'autocad-drawings-group-of-people-groups-blocks-dwg-dxf.png';     // Group of people ( 2023-07-11 borre esta categoria )

            $c[2]['main_title'] = 'autocad-drawings-blocks-templates-symbols-dwg-dxf.png';   // Symbols
            $c[2]['cat2'][3]['main_title'] = 'north-arrows-autocad-blocks-drawings-templates-dwg-dxf.png'; // North Arrows
            $c[5]['main_title'] = 'autocad-vehicles-drawings-blocks-templates-dwg-dxf.png';  // Vehicles
            $c[5]['cat2'][7]['main_title'] = 'autocad-aircraft-drawings-blocks-templates-dwg-dxf.png';    // Aircrafts
            $c[5]['cat2'][5]['main_title'] = 'autocad-cars-motor-vehicles-drawings-blocks-dwg-dxf.png';   // Cars
            $c[5]['cat2'][6]['main_title'] = 'autocad-ships-boats-drawings-blocks-templates-dwg-dxf.png'; // Ships and boats
            $c[5]['cat2'][11]['main_title'] = 'autocad-bikes-motorcycles-bicycles-drawings-blocks-dwg-dxf.png'; // Bikes & Motorcycles

            // $c[]['main_title'] = '';
            // $c[]['cat2'][]['main_title'] = '';
            // ----- MAIN TITLE IMG -------------------------------------------------------------------------------------------

            c_cacher::array_save($token, $c);
        }
        return $c;
    }



}

