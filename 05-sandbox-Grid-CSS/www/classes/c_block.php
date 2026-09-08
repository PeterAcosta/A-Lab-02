<?php
class c_block{
public $cant = 0;
public $campos_mini_visor_1 = 't1.block_key,t1.cat1_key,t1.cat2_key,t1.last_downloads_average,t1.block_ucs,t1.lib_key,t1.block_description,t1.base_name_gif,t1.url_alias,t1.block_title,t1.block_day,t2.lib_url_alias';
public $campos_mini_visor_2 = 't1.block_key,t1.cat1_key,t1.cat2_key,t1.last_downloads_average,t1.block_ucs,t1.lib_key,t1.block_description,t1.base_name_gif,t1.url_alias,t1.block_day,t1.base_name,t1.top_ranking,t1.view_total,t1.download_total,t1.last_downloads,t1.block_scale,t1.block_cost,t2.lib_url_alias';
public function cantidad(){
if ($this->cant == 0){
$resultado = mysqli_query($GLOBALS['link'], 'SELECT COUNT(*) FROM blocks AS t1') or die('ERROR al intentar contar la tabla : bloques');
$fila = mysqli_fetch_array($resultado);
$this->cant = $fila['COUNT(*)'];}
return $this->cant;}
public function go($clave, $goto = ''){
$goto = strtolower($goto);
$hacer_querry = true;
$campos = '*';
if ($clave > 0 and $goto == ''){
$sql = "SELECT $campos FROM blocks WHERE block_key = $clave";
} elseif ($goto == 'first'){
$sql = "SELECT $campos FROM blocks ORDER BY block_key LIMIT 1";
} elseif ($goto == 'next'){
$sql = "SELECT $campos FROM blocks WHERE block_key>$clave ORDER BY block_key LIMIT 1";
} elseif ($goto == 'previous'){
$sql = "SELECT $campos FROM blocks WHERE block_key<$clave ORDER BY block_key DESC LIMIT 1";
} elseif ($goto == 'last'){
$sql = "SELECT $campos FROM blocks ORDER BY block_key DESC LIMIT 1";
} else {
$sql = '';
$hacer_querry = false;}
if ($hacer_querry){
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->go( $clave ) - $sql " . mysqli_error($GLOBALS['link']));
$fila = mysqli_fetch_assoc($resultado);
} else {
$fila = false;}
if ($fila){
$fila['existe'] = true;
} else {
$fila['block_key'] = $clave;
$fila['existe'] = false;}
return $fila;}
public function first_last(){
if (!isset($_SESSION['tablas']['blocks']['first'])){
$sql = 'SELECT block_key FROM blocks ORDER BY block_key LIMIT 1';
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->first_last() - $sql " . mysqli_error($GLOBALS['link']));
$fila = mysqli_fetch_array($resultado);
$_SESSION['tablas']['blocks']['first'] = $fila['block_key'];}
if (!isset($_SESSION['tablas']['blocks']['last'])){
$sql = 'SELECT block_key FROM blocks ORDER BY block_key DESC LIMIT 1';
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->first_last() - $sql " . mysqli_error($GLOBALS['link']));
$fila = mysqli_fetch_array($resultado);
$_SESSION['tablas']['blocks']['last'] = $fila['block_key'];}}
public function AddNew(){
$largo_minimo = 8;
$sql = "SELECT block_key FROM blocks WHERE LENGTH(base_name)<$largo_minimo ORDER BY block_key LIMIT 1";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
$fila = mysqli_fetch_array($resultado);
if ($fila){
$new_key = $fila['block_key'];
} else {
$new_file = 'New-block';
$user = $_SESSION['user']['key'];
$dia = date("Ymd");
$sql = 'INSERT INTO blocks SET  base_name="' . $new_file . '"';
$sql .= ', block_description="new block is coming; description of the new block"';
$sql .= ', user_key=' . $user;
$sql .= ', days_sum=1';
$sql .= ', block_day="' . $dia . '"';
$sql .= ', url_alias=""';
$sql .= ', block_keywords="autocad,drawing,block,templates,symbols,models,cad,dwg,dxf,"';
$sql .= ', base_name_gif="' . $new_file . '"';
$sql .= ', base_name_png="' . $new_file . '"';
mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->AddNew() - $sql <br />" . mysqli_error($GLOBALS['link']));
$new_key = mysqli_insert_id($GLOBALS['link']);
unset($_SESSION['tablas']['blocks']['cantidad']);
unset($_SESSION['tablas']['blocks']['first']);
unset($_SESSION['tablas']['blocks']['last']);}
return $new_key;}
public function existe_en_folder($folder, $block_key){
chdir($folder);
$patron = '*-' . $block_key . '.*';
return glob($patron);}
public function construye_condicion($page){
$tab_menu = $page['tab_menu'];
$cat1 = $page['cat1'];
$cat2 = $page['cat2'];
$lib_key = $page['lib_key'];
$view = $page['view'];
$keys = $page['keys'];
if ($tab_menu != 1){
die('error en tab_menu.');}
if ($_SESSION ['user'] ['permit'] == 9){
$cond_0 = "t1.block_activo >= 1";
}else{
$cond_0 = "t1.block_activo = 2";}
if ($cat1 == 0){
$cond_1 = '';
} else {
if ($cat2 == 0){
$cond_1 = "t1.cat1_key=$cat1";
} else {
$cond_1 = "t1.cat1_key=$cat1 AND t1.cat2_key=$cat2";}}
if ($lib_key != 0){
if ($lib_key > 0){
$cond_1 = '';
$cond_2 = "t1.lib_key=$lib_key";
} else {
$cond_2 = 't1.lib_key>0';}} else {
$cond_2 = '';}
if ($view != 'X' && $view != ''){
$cond_3 = "t1.block_ucs='$view'";
} else {
$cond_3 = '';}
if (strlen($keys) > 1){
$cond_4 = "t1.block_description LIKE '%$keys%'";
} else {
$cond_4 = '';}
$condicion = "WHERE $cond_0";
if ($cond_1 != ''){
$condicion .= ' AND '.$cond_1;}
if ($cond_2 != ''){
$condicion .= ' AND '.$cond_2;}
if ($cond_3 != ''){
$condicion .= ' AND '.$cond_3;}
if ($cond_4 != ''){
$condicion .= ' AND '.$cond_4;}
return $condicion;}
public function cantidad_blocks_list($page){
$sql = "SELECT COUNT(*) FROM blocks AS t1 {$this->construye_condicion($page)}";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->cantidad_blocks_list($page) - $sql <br />" . mysqli_error($GLOBALS['link']));
$row = mysqli_fetch_row($resultado);
return $row[0];}
public function get_blocks_list($page, $options){
if ($options['mini_visor'] == 2){
$campos = $this->campos_mini_visor_2;
} else {
$campos = $this->campos_mini_visor_1;}
$condicion = $this->construye_condicion($page);
if ($page['lib_key'] == -1){
$orden_campo = 'ORDER BY t1.lib_last_downloads_average DESC , t1.last_downloads_average DESC';
} elseif ($options['order_by'] == 'T'){
$orden_campo = 'ORDER BY t1.top_ranking';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} elseif ($options['order_by'] == 'A'){
$orden_campo = 'ORDER BY t1.last_downloads_average';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} elseif ($options['order_by'] == 'B'){
$orden_campo = 'ORDER BY t1.last_downloads';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} elseif ($options['order_by'] == 'C'){
$orden_campo = 'ORDER BY t1.download_average';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} elseif ($options['order_by'] == 'D'){
$orden_campo = 'ORDER BY t1.download_total';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} elseif ($options['order_by'] == 'E'){
$orden_campo = 'ORDER BY t1.view_average';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} elseif ($options['order_by'] == 'F'){
$orden_campo = 'ORDER BY t1.view_total';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} elseif ($options['order_by'] == 'G'){
$orden_campo = 'ORDER BY t1.popular';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} elseif ($options['order_by'] == 'H'){
$orden_campo = 'ORDER BY t1.lib_key';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} elseif ($options['order_by'] == 'I'){
$orden_campo = 'ORDER BY t1.block_ucs';
$orden_campo .= ($options['order'] == 'ASC') ? ' DESC' : '';
} elseif ($options['order_by'] == 'J'){
$orden_campo = 'ORDER BY t1.days_sum';
$orden_campo .= ($options['order'] == 'ASC') ? '' : ' DESC';
} else {
$orden_campo = '';}
$lim_1 = $options['per_page'][$options['mini_visor']] * ($page['page'] - 1);
$lim_2 = $options['per_page'][$options['mini_visor']];
$sql = "SELECT $campos FROM blocks as t1 LEFT JOIN libs as t2 ON t1.lib_key = t2.lib_key $condicion $orden_campo LIMIT $lim_1,$lim_2";
$result = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->busca_blocks_list($page) - $sql <br />" . mysqli_error($GLOBALS['link']));
$blocks_list = array();
$block_list_reduced = array();
while ($aux = mysqli_fetch_assoc($result)){
$blocks_list[$aux['block_key']] = $aux;
$block_list_reduced[$aux['block_key']] = $aux['url_alias'];}
$_SESSION['block_list_reduced'] = $block_list_reduced;
mysqli_free_result($result);
return $blocks_list;}
public function get_new_blocks($cantidad, $dias = 30, $mini_visor = 1){
$t0 = microtime(true);
$fecha_nuevos =  date('Y-m-d', strtotime("-$dias days"));
if ($mini_visor == 2){
$campos = $this->campos_mini_visor_2;
} else {
$campos = $this->campos_mini_visor_1;}
if ($_SESSION ['user'] ['permit'] == 9){
$block_activo = 1;
}else{
$block_activo = 2;}
$sql_1 = "SELECT $campos
FROM blocks as t1 LEFT JOIN libs as t2 ON t1.lib_key = t2.lib_key
WHERE t1.block_activo >= $block_activo AND t1.block_day > '$fecha_nuevos'
ORDER BY t1.block_key DESC
LIMIT  $cantidad ";
$result = mysqli_query($GLOBALS['link'], $sql_1) or die("ERROR - c_block->busca_new_blocks 1");
$new_blocks = array();
$nuevos=0;
while ($b = mysqli_fetch_assoc($result)){
$new_blocks['list'][$b['block_key']] = $b;
$new_blocks['reduced'][-$b['block_key']] = $b['url_alias'];
$nuevos++;}
mysqli_free_result($result);
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
$result = mysqli_query($GLOBALS['link'], $sql_2) or die("ERROR - c_block->busca_new_blocks 2");
while ($b = mysqli_fetch_assoc($result)){
$new_blocks['list'][$b['block_key']] = $b;
$new_blocks['reduced'][-$b['block_key']] = $b['url_alias'];}
mysqli_free_result($result);}
$new_blocks['nuevos']=$nuevos;
$new_blocks['faltantes']=$faltantes;
$_SESSION['time']['30_new_blocks_db'] = round(microtime(true) - $t0, 4);
return $new_blocks;}
public function get_lib_mates($block_key, $lib_key, $cat1_key, $cat2_key, $cant = 0, $mini_visor = 1, $lib_mates_only = true){
if ($mini_visor == 2){
$campos = $this->campos_mini_visor_2;
} else {
$campos = $this->campos_mini_visor_1;}
$blocks_list = array();
$cant_aux = 0;
if ($lib_key > 0){
$and_cond_1 = ($block_key > 0) ? 'AND t1.block_key!=' . $block_key : '';
$limit = ($cant > 0) ? 'LIMIT ' . $cant : '';
$sql = "SELECT $campos
FROM blocks as t1 LEFT JOIN libs as t2 ON t1.lib_key = t2.lib_key
WHERE t1.lib_key=$lib_key $and_cond_1
ORDER BY t1.block_key " . $limit;
$result = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->get_lib_mates($lib_key) - $sql " . mysqli_error($GLOBALS['link']));
while ($aux = mysqli_fetch_assoc($result)){
$blocks_list[] = $aux;
$cant_aux++;}
mysqli_free_result($result);}
if ($cant > $cant_aux && !$lib_mates_only){
$dif = $cant - $cant_aux;
$and_cond_2 = ($lib_key > 0) ? "t1.lib_key!=$lib_key AND" : '';
$sql = "SELECT $campos
FROM blocks as t1 LEFT JOIN libs as t2 ON t1.lib_key = t2.lib_key
WHERE $and_cond_2 t1.blocK_key<>$block_key AND t1.cat1_key=$cat1_key AND t1.cat2_key=$cat2_key
ORDER BY t1.last_downloads_average DESC
LIMIT $dif";
$result = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_block->get_lib_mates($lib_key) - $sql " . mysqli_error($GLOBALS['link']));
while ($aux = mysqli_fetch_assoc($result)){
$blocks_list[] = $aux;
$cant_aux++;}
mysqli_free_result($result);}
return $blocks_list;}
public static function get_url_alias($block_key, $full = 0){
$result = mysqli_query($GLOBALS['link'], 'SELECT url_alias FROM blocks WHERE block_key=' . abs($block_key)) or die('ERROR al intentar obtener block_url_alias');
$fila = mysqli_fetch_assoc($result);
return c_block::aux_url_alias($block_key, $fila['url_alias'], $full);}
public static function full_url_alias($block_key, $cat1_key, $cat2_key, $view, $block_name, $full = 0){
$url_alias = '';
if ($cat1_key > 0){
global $CATs;
$url_alias .= $CATs[$cat1_key]['url_alias'] . '/';
if ($cat2_key){
$url_alias .= $CATs[$cat1_key]['cat2'][$cat2_key]['url_alias'] . '/';}}
if ($view != 'X'){
$url_alias .= CECO_UCS_VIEWS_URL_ALIAS[$view] . '/';}
$url_alias .= CECO_URL_ALIAS_ID_BLOCK . name_to_url_alias($block_name);
return c_block::aux_url_alias($block_key, $url_alias, $full);}
public static function aux_url_alias($block_key, $url_alias, $full = 0){
if ($full > 0){
$url_alias = CECO_URL_ALIAS_BASE_BLOCKS . '/' . $url_alias;
$url_alias .= ($block_key > 0) ? CECO_URL_ALIAS_FINAL_OLD_BLOCK : CECO_URL_ALIAS_FINAL_NEW_BLOCK;
$url_alias .= abs($block_key);
if ($full == 2){
$url_alias = CECO_URL_DOMINIO . '/' . $url_alias;}}
return $url_alias;}
public static function get_full_url_image($block_key, $tipo, $base_name_x){
if ($tipo == 'gif'){
$full_url_image = CECO_URL_DOMINIO . '/' . CECO_FOLDER_IMG_MINI . $base_name_x . CECO_BASE_NAME_GIF_PNG_FINAL . $block_key . '.gif';
} elseif ($tipo == 'png'){
$full_url_image = CECO_URL_DOMINIO . '/' . CECO_FOLDER_IMG_MAXI . $base_name_x . CECO_BASE_NAME_GIF_PNG_FINAL . $block_key . '.png';
} else {
$full_url_image = 'ERROR get_url_images';}
return $full_url_image;}}
