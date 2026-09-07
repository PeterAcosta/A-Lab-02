<?php

#-------------------------------------------------------------------------------------------------------------
# Class name    : c_block
# Class created : martes 24 de febrero de 2009 por Peter Acosta
# Class description : bloques
#-------------------------------------------------------------------------------------------------------------


#1.  __construct()
#2.  __destruct()
#3.  cantidad()
#4.  go($clave,$goto='')
#5.  first_last()
#6.  AddNew()
#7.  existe_en_folder($folder,$block_key)
#8.  construye_condicion($page)
#9.  cantidad_blocks_list($page)
#10. busca_blocks_list($page,$options)
#11. busca_new_blocks($cantidad)
#12. get_lib_mates($block_key, $lib_key, $cat1_key ,$cat2_key,$cant = 0, $mini_visor = 1, $lib_mates_only = true)
#13. get_url_alias($block_key)
#14. construct_url_alias($block_key, $cat1, $cat2, $view, $block_description, $full = 0, $new = false)
#
#


class c_block
{
    public $cant = 0;
    public $campos_mini_visor_1 = 't1.block_key,t1.cat1_key,t1.cat2_key,t1.last_downloads_average,t1.block_ucs,t1.lib_key,t1.block_description,t1.base_name_gif,t1.url_alias,t1.block_title,t1.block_day,t2.lib_url_alias';
    public $campos_mini_visor_2 = 't1.block_key,t1.cat1_key,t1.cat2_key,t1.last_downloads_average,t1.block_ucs,t1.lib_key,t1.block_description,t1.base_name_gif,t1.url_alias,t1.block_day,t1.base_name,t1.top_ranking,t1.view_total,t1.download_total,t1.last_downloads,t1.block_scale,t1.block_cost,t2.lib_url_alias';

    #---------------------------------------------------------------------------------------------------------3
    # function   : cantidad()
    # Created    : martes 24 de febrero de 2009 por Peter Acosta
    # Parameters : none
    # Variables  : INT $_SESSION['blocks']['cantidad']
    # Return     : INT con la cantidad de registros que existen en la tabla bloques
    #---------------------------------------------------------------------------------------------------------
    public function cantidad()
    {
        if ($this->cant == 0) {
            $resultado = mysqli_query($GLOBALS['link'], 'SELECT COUNT(*) FROM blocks AS t1') or die('ERROR al intentar contar la tabla : bloques');
            $fila = mysqli_fetch_array($resultado);
            $this->cant = $fila['COUNT(*)'];
        }
        return $this->cant;
    }

    /** ** ******************************************************************************************************************************* 4
     * @example go($clave,$goto='')
     * @since   miercoles 25 de febrero de 2009 por Peter Acosta
     * @param   int $clave
     * @param   string $goto
     * @return  array con los campos del bloque encontrado
     */
    public function go($clave, $goto = '')
    {
        $goto = strtolower($goto);
        $hacer_querry = true;
        $campos = '*';
        if ($clave > 0 and $goto == '') {
            $sql = "SELECT $campos FROM blocks WHERE block_key = $clave";
        } elseif ($goto == 'first') {
            $sql = "SELECT $campos FROM blocks ORDER BY block_key LIMIT 1";
        } elseif ($goto == 'next') {
            $sql = "SELECT $campos FROM blocks WHERE block_key>$clave ORDER BY block_key LIMIT 1";
        } elseif ($goto == 'previous') {
            $sql = "SELECT $campos FROM blocks WHERE block_key<$clave ORDER BY block_key DESC LIMIT 1";
        } elseif ($goto == 'last') {
            $sql = "SELECT $campos FROM blocks ORDER BY block_key DESC LIMIT 1";
        } else {
            $sql = '';
            $hacer_querry = false;
        }
        if ($hacer_querry) {
            $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->go( $clave ) - $sql " . mysqli_error($GLOBALS['link']));
            $fila = mysqli_fetch_assoc($resultado);
        } else {
            $fila = false;                  # aqui hay otro parrafo 
        }
        if ($fila) {
            $fila['existe'] = true;          #c esto es un comentario 
        } else {
            $fila['block_key'] = $clave;  #### esto es un comentario 
            $fila['existe'] = false;        // esto es un comentario 
        }

        return $fila;       ///// AQUI VA OTRO COMENTARIIO 
    }



    #---------------------------------------------------------------------------------------------------------5
    # function   : first_last()
    # Created    : jueves 26 de febrero de 2009 por Peter Acosta
    # Parameters : none
    # Variables  : INT $_SESSION['tablas']['blocks']['first']   y  $_SESSION['tablas']['blocks']['last']
    # Return     : none
    #---------------------------------------------------------------------------------------------------------
    public function first_last()
    {
        if (!isset($_SESSION['tablas']['blocks']['first'])) {
            $sql = 'SELECT block_key FROM blocks ORDER BY block_key LIMIT 1';
            $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->first_last() - $sql " . mysqli_error($GLOBALS['link']));
            $fila = mysqli_fetch_array($resultado);
            $_SESSION['tablas']['blocks']['first'] = $fila['block_key'];
        }
        if (!isset($_SESSION['tablas']['blocks']['last'])) {
            $sql = 'SELECT block_key FROM blocks ORDER BY block_key DESC LIMIT 1';
            $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->first_last() - $sql " . mysqli_error($GLOBALS['link']));
            $fila = mysqli_fetch_array($resultado);
            $_SESSION['tablas']['blocks']['last'] = $fila['block_key'];
        }
    }



    #---------------------------------------------------------------------------------------------------------6
    # function   : AddNew()
    # Created    : lunes 2 de marzo de 2009 por Peter Acosta
    # Parameters : none
    # Return     : INT con el key del bloque que se acaba de agregar
    #---------------------------------------------------------------------------------------------------------
    public function AddNew()
    {
        $largo_minimo = 8;
        $sql = "SELECT block_key FROM blocks WHERE LENGTH(base_name)<$largo_minimo ORDER BY block_key LIMIT 1";
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
        $fila = mysqli_fetch_array($resultado);
        if ($fila) {
            # Ya hay un block vacio sin usar
            $new_key = $fila['block_key'];
        } else {
            # Creo un nuevo bloque
            $new_file = 'New-block';
            $user = $_SESSION['user']['key'];
            $dia = date("Ymd");
            $sql = 'INSERT INTO blocks SET  base_name="' . $new_file . '"';
            $sql .= ', block_description="new block is coming ; description of the new block"';
            $sql .= ', user_key=' . $user;
            $sql .= ', days_sum=1';
            $sql .= ', block_day="' . $dia . '"';
            $sql .= ', url_alias=""';
            $sql .= ', block_keywords="autocad,drawing,block,templates,symbols,models,cad,dwg,dxf,"';
            $sql .= ', base_name_gif="' . $new_file . '"';
            $sql .= ', base_name_png="' . $new_file . '"';
            mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->AddNew() - $sql <br />" . mysqli_error($GLOBALS['link']));
            $new_key = mysqli_insert_id($GLOBALS['link']);
            #acomodo otros parametros como : cantidad , primero y ultimo
            unset($_SESSION['tablas']['blocks']['cantidad']);
            unset($_SESSION['tablas']['blocks']['first']);
            unset($_SESSION['tablas']['blocks']['last']);
        }
        return $new_key;
    }


    #---------------------------------------------------------------------------------------------------------7
    # function   : existe_en_folder()
    # Created    : miercoles 17 de diciembre de 2008 y arregaldo el jueves 5 de marzo por Peter Acosta
    # Parameters : STRING con la ruta de una carpeta valida
    # Return     : ARRAY con todos los NOMBRES que encontro en esa carpeta que conincidan con el actual bloque
    #---------------------------------------------------------------------------------------------------------
    public function existe_en_folder($folder, $block_key)
    {
        chdir($folder);
        $patron = '*-' . $block_key . '.*';
        return glob($patron);
    }




    #---------------------------------------------------------------------------------------------------------8
    # function   : construye_condicion($page)
    # Created    : Viernes 14 de agosto de 2009 por Peter Acosta
    # Parameters : ARRAY $page[] con datos a buscar
    # Return     : String con la condicion "WHERE ....... " que corresponden al $page
    # 
    # Actualizado el 27 de Julio de 2023 para que soporte el filtro de quien puede ver el bloque 
    #---------------------------------------------------------------------------------------------------------
    public function construye_condicion($page)
    {
        $tab_menu = $page['tab_menu'];
        $cat1 = $page['cat1'];
        $cat2 = $page['cat2'];
        $lib_key = $page['lib_key'];
        $view = $page['view'];
        $keys = $page['keys'];
        // $block_key = $page['block_key'];


        # Tab_menu ----------------------------------------------------------
        # aqui deberia llegar solo tab_menu = 1
        if ($tab_menu != 1) {
            die('error en tab_menu.');
        }

        ## block_Activo -----------------------------------------------------
        ## block_activo : 0 nadie puede ver este  bloque  ;  1 solo admin puede verto  ;  2 todos pueden verlo (default)
        if ($_SESSION ['user'] ['permit'] == 9) {
            // ES ADMIN
            $cond_0 = "t1.block_activo >= 1";
        }else{
            // no es Admin, es un usuario comun
            $cond_0 = "t1.block_activo = 2";
        }


        # Cat1 & Cat2 -------------------------------------------------------
        if ($cat1 == 0) {
            $cond_1 = '';
        } else {
            if ($cat2 == 0) {
                $cond_1 = "t1.cat1_key=$cat1";
            } else {
                $cond_1 = "t1.cat1_key=$cat1 AND t1.cat2_key=$cat2";
            }
        }
        # lib_Key  -----------------------------------------------------------
        if ($lib_key != 0) {
            if ($lib_key > 0) {
                # Muestro ESA libreria NN
                $cond_1 = '';
                $cond_2 = "t1.lib_key=$lib_key";
            } else {
                # Muestro todas las librerias
                $cond_2 = 't1.lib_key>0';
            }
        } else {
            $cond_2 = '';
        }

        # view --------------------------------------------------------------
        if ($view != 'X' && $view != '') {
            $cond_3 = "t1.block_ucs='$view'";
        } else {
            $cond_3 = '';
        }
        # Keys --------------------------------------------------------------
        if (strlen($keys) > 1) {
            $cond_4 = "t1.block_description LIKE '%$keys%'";
        } else {
            $cond_4 = '';
        }



        ### ARMADO final ------------------------------------------------------------------
        $condicion = "WHERE $cond_0" ;
        if ($cond_1 != '') {
            $condicion .= ' AND '.$cond_1 ;
        }    
        if ($cond_2 != '') {
            $condicion .= ' AND '.$cond_2 ;
        }    
        if ($cond_3 != '') {
            $condicion .= ' AND '.$cond_3 ;
        }    
        if ($cond_4 != '') {
            $condicion .= ' AND '.$cond_4 ;
        }
        ### ARMADO final ------------------------------------------------------------------
        # ppp('condision',$condicion,4);
        return $condicion;
    }


    #---------------------------------------------------------------------------------------------------------9
    # function   : cantidad_blocks_list($page)
    # Created    : Viernes 14 de agosto de 2009 por Peter Acosta
    # Parameters : ARRAY $page[] con datos a buscar
    # Return     : INT con la cantidad de registros que cumplen la condicion de $page[]
    #---------------------------------------------------------------------------------------------------------
    public function cantidad_blocks_list($page)
    {
        $sql = "SELECT COUNT(*) FROM blocks AS t1 {$this->construye_condicion($page)}";
        $resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->cantidad_blocks_list($page) - $sql <br />" . mysqli_error($GLOBALS['link']));
        $row = mysqli_fetch_row($resultado);
        return $row[0];
    }


    /** ** ****************************************************************************************************************************** 10
     * @example get_blocks_list($page, $options)
     * @since   Lunes 13 de julio de 2009
     *          actualizado viernes 4 DE AGOSTO DE 2023
     * @author  Peter Acosta
     * @param   array $page
     * @param   array $options
     * @return  array $blocks_list ( un array con una lista de bloques que complen con las condiciones de $page )
     */
    public function get_blocks_list($page, $options)
    {
        if ($options['mini_visor'] == 2) {
            # Maxi Visor
            $campos = $this->campos_mini_visor_2;
        } else {
            # Mini Visor
            $campos = $this->campos_mini_visor_1;
        }

        # Condiciones -------------------------------------------------------
        $condicion = $this->construye_condicion($page);
        # Condiciones -------------------------------------------------------

        ## ORDER BY ----------------------------------------------------------
        ## A  last_downloads_average
        ## B  last_downloads
        ## C  download_average
        ## D  download_total
        ## E  view_average
        ## F  view_total 
        ## G  popular
        ## H  lib_key
        ## I  block_ucs
        ## J  days_sum
        ## T  top_ranking

        if ($page['lib_key'] == -1) {
            $orden_campo = 'ORDER BY t1.lib_last_downloads_average DESC , t1.last_downloads_average DESC';
        } elseif ($options['order_by'] == 'T') {
            $orden_campo = 'ORDER BY t1.top_ranking';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';    
        } elseif ($options['order_by'] == 'A') {
            $orden_campo = 'ORDER BY t1.last_downloads_average';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
        } elseif ($options['order_by'] == 'B') {
            $orden_campo = 'ORDER BY t1.last_downloads';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
        } elseif ($options['order_by'] == 'C') {
            $orden_campo = 'ORDER BY t1.download_average';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
        } elseif ($options['order_by'] == 'D') {
            $orden_campo = 'ORDER BY t1.download_total';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
        } elseif ($options['order_by'] == 'E') {
            $orden_campo = 'ORDER BY t1.view_average';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
        } elseif ($options['order_by'] == 'F') {
            $orden_campo = 'ORDER BY t1.view_total';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
        } elseif ($options['order_by'] == 'G') {
            $orden_campo = 'ORDER BY t1.popular';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
        } elseif ($options['order_by'] == 'H') {
            $orden_campo = 'ORDER BY t1.lib_key';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
        } elseif ($options['order_by'] == 'I') {
            $orden_campo = 'ORDER BY t1.block_ucs';
            $orden_campo .= ($options['order'] == 'ASC') ? ' DESC' : '';
        } elseif ($options['order_by'] == 'J') {
            $orden_campo = 'ORDER BY t1.days_sum';
            $orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
        } else {
            $orden_campo = '';
        }
        ## ORDER BY ----------------------------------------------------------

        # LIMIT ---------------------------------------------------------------------
        $lim_1 = $options['per_page'][$options['mini_visor']] * ($page['page'] - 1);
        $lim_2 = $options['per_page'][$options['mini_visor']];
        # LIMIT ---------------------------------------------------------------------

        $sql = "SELECT $campos FROM blocks as t1 LEFT JOIN libs as t2 ON t1.lib_key = t2.lib_key $condicion $orden_campo LIMIT $lim_1,$lim_2";
        $result = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->busca_blocks_list($page) - $sql <br />" . mysqli_error($GLOBALS['link']));

        $blocks_list = array();
        $block_list_reduced = array();
        while ($aux = mysqli_fetch_assoc($result)) {
            $blocks_list[$aux['block_key']] = $aux;
            $block_list_reduced[$aux['block_key']] = $aux['url_alias'];
        }
        $_SESSION['block_list_reduced'] = $block_list_reduced;

        mysqli_free_result($result);
        return $blocks_list;
    }




    /** ** ****************************************************************************************************************************** 11
     * @example get_new_blocks($cantidad,$mini_visor=1)
     * @since   Jueves 26 de septiembre de 2013 por Peter Acosta
     * @author  Peter Acosta
     * @param   int $cantidad
     * @param   int $dias           
     * @param   int $mini_visor
     * @return  array $blocks_list ( un array con la lista de los nuevos bloques )
     */
    public function get_new_blocks($cantidad, $dias = 30, $mini_visor = 1)
    {
        $t0 = microtime(true);
        $fecha_nuevos =  date('Y-m-d', strtotime("-$dias days"));
        
        if ($mini_visor == 2) {
            $campos = $this->campos_mini_visor_2;
        } else {
            $campos = $this->campos_mini_visor_1;
        }

        ## 0 nadie puede verlo ; 1 solo admin puede verto ; 2 todos pueden verlo	
        if ($_SESSION ['user'] ['permit'] == 9) {
            // ES ADMIN
            $block_activo = 1 ;
        }else{
            // no es Admin, es un usuario comun
            $block_activo = 2 ;
        }

        $sql_1 = "SELECT $campos 
                FROM blocks as t1 LEFT JOIN libs as t2 ON t1.lib_key = t2.lib_key 
                WHERE t1.block_activo >= $block_activo AND t1.block_day > '$fecha_nuevos'
                ORDER BY t1.block_key DESC 
                LIMIT  $cantidad ";

        $result = mysqli_query($GLOBALS['link'], $sql_1) or die("ERROR - c_block->busca_new_blocks 1");
        $new_blocks = array();
        $nuevos=0;
        while ($b = mysqli_fetch_assoc($result)) {
            $new_blocks['list'][$b['block_key']] = $b;
            $new_blocks['reduced'][-$b['block_key']] = $b['url_alias'];
            $nuevos++ ;
        }
        mysqli_free_result($result);
        # echo "<h1> ----------------------------------------------------------------------- > $sql_1 <br> $fecha_nuevos \$nuevos : $nuevos  </h1>" ;

        $faltantes=$cantidad-$nuevos;
        if ( $faltantes > 0 ){
            $sql_2="SELECT *
                    FROM (
                        SELECT $campos
                        FROM blocks as t1 LEFT JOIN libs as t2 ON t1.lib_key = t2.lib_key
                        ORDER BY t1.last_downloads_average DESC
                        LIMIT 300 OFFSET 36
                        ) AS t0
                    ORDER BY RAND()
                    LIMIT $faltantes;";
                    # ORDER BY RAND()    
                    # ORDER BY t0.last_downloads_average DESC
            # echo "<h1> ----------------------------------------------------------------------- > $sql_2 <br> \$faltantes  : $faltantes  </h1>" ;

            $result = mysqli_query($GLOBALS['link'], $sql_2) or die("ERROR - c_block->busca_new_blocks 2");
            while ($b = mysqli_fetch_assoc($result)) {
                $new_blocks['list'][$b['block_key']] = $b;
                $new_blocks['reduced'][-$b['block_key']] = $b['url_alias'];
            }
            mysqli_free_result($result);
        }

        $new_blocks['nuevos']=$nuevos;
        $new_blocks['faltantes']=$faltantes;
        $_SESSION['time']['30_new_blocks_db'] = round(microtime(true) - $t0, 4);
        return $new_blocks;
    }




    /** ** ****************************************************************************************************************************** 12
     * @example lib_mates($block_key, $lib_key, $cant = 0, $mini_visor = 1, $lib_mates_only = true)
     * @since Jueves 19 de abril de 2018
     * @author Peter Acosta
     * @param int $block_key
     * @param int $lib_key
     * @param int $cat1_key
     * @param int $cat2_key
     * @param int $cant
     * @param int $mini_visor
     * @param bool $lib_mates_only
     * @return array $block_list ( una array con la lista de bloques que perteneces a esa libreria )
     */
    public function get_lib_mates($block_key, $lib_key, $cat1_key, $cat2_key, $cant = 0, $mini_visor = 1, $lib_mates_only = true)
    {
        if ($mini_visor == 2) {
            # Maxi Visor
            $campos = $this->campos_mini_visor_2;
        } else {
            # Mini Visor
            $campos = $this->campos_mini_visor_1;
        }
        $blocks_list = array();
        $cant_aux = 0;
        if ($lib_key > 0) {
            $and_cond_1 = ($block_key > 0) ? 'AND t1.block_key!=' . $block_key : '';
            $limit = ($cant > 0) ? 'LIMIT ' . $cant : '';
            $sql = "SELECT $campos 
                    FROM blocks as t1 LEFT JOIN libs as t2 ON t1.lib_key = t2.lib_key  
                    WHERE t1.lib_key=$lib_key $and_cond_1 
                    ORDER BY t1.block_key " . $limit;
            $result = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->get_lib_mates($lib_key) - $sql " . mysqli_error($GLOBALS['link']));
            while ($aux = mysqli_fetch_assoc($result)) {
                $blocks_list[] = $aux;
                $cant_aux++;
            }
            mysqli_free_result($result);
        }
        if ($cant > $cant_aux && !$lib_mates_only) {
            $dif = $cant - $cant_aux;
            $and_cond_2 = ($lib_key > 0) ? "t1.lib_key!=$lib_key AND" : '';
            $sql = "SELECT $campos 
                    FROM blocks as t1 LEFT JOIN libs as t2 ON t1.lib_key = t2.lib_key 
                    WHERE $and_cond_2 t1.blocK_key<>$block_key AND t1.cat1_key=$cat1_key AND t1.cat2_key=$cat2_key
                    ORDER BY t1.last_downloads_average DESC 
                    LIMIT $dif";
            // ppp('sql',$sql,4);
            $result = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->get_lib_mates($lib_key) - $sql " . mysqli_error($GLOBALS['link']));
            while ($aux = mysqli_fetch_assoc($result)) {
                $blocks_list[] = $aux;
                $cant_aux++;
            }
            mysqli_free_result($result);
        }
        return $blocks_list;
    }


    /** ** ****************************************************************************************************************************** 13
     * @example c_block::get_url_alias($block_key)
     * @since Sabado 12 de Mayo de 2018
     * @author Peter Acosta
     * @param  int $block_key
     * @param int $full ( 0 = solo lo que se guarda en la db , 1 = param , 2 = URL completa )
     * @return string url_alias
     */
    public static function get_url_alias($block_key, $full = 0)
    {
        $result = mysqli_query($GLOBALS['link'], 'SELECT url_alias FROM blocks WHERE block_key=' . abs($block_key)) or die('ERROR al intentar obtener block_url_alias');
        $fila = mysqli_fetch_assoc($result);
        return c_block::aux_url_alias($block_key, $fila['url_alias'], $full);
    }


    /** ** ****************************************************************************************************************************** 14
     * @example c_block::$full_url_alias($block_key, $cat1, $cat2, $view, $block_description, $full = 0, $new = false)
     * @since Miercoles 16 de mayo de 2018
     * @author Peter Acosta
     * Construye la URL_ALIAS de los bloques para "param" o "URL
     * @param int $block_key
     * @param int $cat1_key
     * @param int $cat2_key
     * @param string $view ( X T F S R E C A P 3 )
     * @param string $block_name
     * @param int $full ( 0 = solo lo que se guarda en la db , 1 = param , 2 = URL completa )
     * @return string $full_url_alias
     */
    public static function full_url_alias($block_key, $cat1_key, $cat2_key, $view, $block_name, $full = 0)
    {
        $url_alias = '';
        if ($cat1_key > 0) {
            global $CATs;
            $url_alias .= $CATs[$cat1_key]['url_alias'] . '/';
            if ($cat2_key) {
                $url_alias .= $CATs[$cat1_key]['cat2'][$cat2_key]['url_alias'] . '/';
            }
        }
        if ($view != 'X') {
            $url_alias .= CECO_UCS_VIEWS_URL_ALIAS[$view] . '/';
        }
        $url_alias .= CECO_URL_ALIAS_ID_BLOCK . name_to_url_alias($block_name);

        return c_block::aux_url_alias($block_key, $url_alias, $full);
    }


    /** ** ****************************************************************************************************************************** 15
     * @example aux_url_alias($block_key, $url_alias, $full = 0)
     * @since Martes 5 de Junio de 2018
     * Auxiliar para los metodos estaticos c_block::get_url_alias() y c_block::$full_url_alias()
     * @param int $block_key
     * @param string $url_alias
     * @param int $full
     * @return string $url_alias
     */
    public static function aux_url_alias($block_key, $url_alias, $full = 0)
    {
        if ($full > 0) {
            $url_alias = CECO_URL_ALIAS_BASE_BLOCKS . '/' . $url_alias;
            $url_alias .= ($block_key > 0) ? CECO_URL_ALIAS_FINAL_OLD_BLOCK : CECO_URL_ALIAS_FINAL_NEW_BLOCK;
            $url_alias .= abs($block_key);
            if ($full == 2) {
                $url_alias = CECO_URL_DOMINIO . '/' . $url_alias;
            }
        }
        return $url_alias;
    }



    /** ** ****************************************************************************************************************************** 16
     * @example c_block::get_full_url_image($block_key, $tipo, $base_name_x)
     * @param int $block_key
     * @param string $tipo ( "gif"  o "png" )
     * @param string $base_name_x
     * @return string $full_url_image
     */
    public static function get_full_url_image($block_key, $tipo, $base_name_x)
    {
        if ($tipo == 'gif') {
            $full_url_image = CECO_URL_DOMINIO . '/' . CECO_FOLDER_IMG_MINI . $base_name_x . CECO_BASE_NAME_GIF_PNG_FINAL . $block_key . '.gif';
        } elseif ($tipo == 'png') {
            $full_url_image = CECO_URL_DOMINIO . '/' . CECO_FOLDER_IMG_MAXI . $base_name_x . CECO_BASE_NAME_GIF_PNG_FINAL . $block_key . '.png';
        } else {
            $full_url_image = 'ERROR get_url_images';
        }

        return $full_url_image;
    }


}

