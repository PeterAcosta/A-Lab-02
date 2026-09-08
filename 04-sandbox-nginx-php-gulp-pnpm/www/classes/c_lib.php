<?php
class c_lib{
public $cant = 0;
public function cantidad(){
if ($this->cant == 0){
$resultado = mysqli_query($GLOBALS['link'], 'SELECT COUNT(*) AS cant FROM libs') or die('ERROR al intentar contar la tabla Libs  - ');
$fila = mysqli_fetch_assoc($resultado);
$this->cant = $fila['cant'];}
return $this->cant;}
public function go($clave, $goto = ''){
$goto = strtolower($goto);
$hacer_querry = true;
if ($clave > 0 and $goto == ''){
$sql = 'SELECT * FROM libs WHERE lib_key =' . $clave;
} elseif ($goto == 'first'){
$sql = "SELECT * FROM libs ORDER BY lib_key LIMIT 1";
} elseif ($goto == 'next'){
$sql = "SELECT * FROM libs WHERE lib_key>$clave ORDER BY lib_key LIMIT 1";
} elseif ($goto == 'previous'){
$sql = "SELECT * FROM libs WHERE lib_key<$clave ORDER BY lib_key DESC LIMIT 1";
} elseif ($goto == 'last'){
$sql = "SELECT * FROM libs ORDER BY lib_key DESC LIMIT 1";
} else {
$hacer_querry = false;}
if ($hacer_querry){
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_lib->go( $clave ) - $sql " . mysqli_error($GLOBALS['link']));
$fila = mysqli_fetch_assoc($resultado);
} else {
$fila = false;}
if ($fila){
$fila['existe'] = true;
} else {
$fila['lib_key'] = $clave;
$fila['existe'] = false;}
return $fila;}
public function first_last($cat1 = 0, $cat2 = 0){
$cat1 = intval($cat1);
if ($cat1 > 0){
$cat2 = intval($cat2);
if ($cat2 > 0){
$where = "WHERE cat1_key=$cat1 AND cat2_key=$cat2";
} else {
$where = "WHERE cat1_key=$cat1";}} else {
$where = '';}
$libs_pos = array('first' => 0, 'last' => 0);
$sql1 = "SELECT lib_key FROM libs $where ORDER BY lib_key LIMIT 1";
$resultado1 = mysqli_query($GLOBALS['link'], $sql1) or die("ERROR - c_lib->first_last() - $sql1 " . mysqli_error($GLOBALS['link']));
$fila1 = mysqli_fetch_assoc($resultado1);
$libs_pos['first'] = $fila1['lib_key'];
$sql2 = "SELECT lib_key FROM libs $where ORDER BY lib_key DESC LIMIT 1";
$resultado2 = mysqli_query($GLOBALS['link'], $sql2) or die("ERROR - c_lib->first_last() - $sql2 " . mysqli_error($GLOBALS['link']));
$fila2 = mysqli_fetch_assoc($resultado2);
$libs_pos['last'] = $fila2['lib_key'];
return $libs_pos;}
function actualiza_days_sum(){
$sql = 'UPDATE libs SET days_sum=DATEDIFF(CURRENT_DATE(),lib_day) , download_average=download_total/IF(days_sum>0,days_sum,1)';
$nada = mysqli_query($GLOBALS['link'], $sql) or die('<br />ERROR MySQL : <br />' . $sql . '<br />' . '<br />' . mysqli_error($GLOBALS['link']));
return;}
function actualiza_blocks_per_lib(){
$sql = 'SELECT lib_key , COUNT(*) FROM blocks WHERE lib_key > 0 GROUP BY lib_key;';
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - mycat1->actualiza_blocks_per_lib() - $sql <br />" . mysqli_error($GLOBALS['link']));
while ($row = mysqli_fetch_assoc($resultado)){
$sql2 = "UPDATE libs SET cant_blocks={$row['COUNT(*)']} WHERE lib_key={$row['lib_key']}";
$resultado2 = mysqli_query($GLOBALS['link'], $sql2) or die("<br />ERROR - mycat1->actualiza_blocks_per_lib() - $sql2 <br />" . mysqli_error($GLOBALS['link']));}}
public function AddNew(){
$largo_minimo = 8;
$sql = "SELECT lib_key FROM libs WHERE LENGTH(lib_file)<$largo_minimo ORDER BY lib_key LIMIT 1";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_libs->AddNew() 1 - $sql " . mysqli_error($GLOBALS['link']));
$fila = mysqli_fetch_assoc($resultado);
if ($fila){
$new_key = $fila['lib_key'];
} else {
$new_file = 'New Lib';
$user = $_SESSION['user']['key'];
$dia = date("Ymd");
$sql = 'INSERT INTO libs SET lib_file="' . $new_file . '"';
$sql .= ', user_key=' . $user;
$sql .= ', days_sum=1';
$sql .= ', lib_day="' . $dia . '"';
mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_libs->AddNew() 2 - $sql " . mysqli_error($GLOBALS['link']));
$new_key = mysqli_insert_id($GLOBALS['link']);
$_SESSION['tablas']['libs']['cantidad']++;}
return $new_key;}
public function existe_en_folder($folder, $lib_key){
$current_folder = getcwd();
chdir($folder);
$result = glob('*-' . $lib_key . '.*');
chdir($current_folder);
return $result;}
public function blocks_in_lib($lib_key){
$campos = '*';
$sql = "SELECT $campos FROM blocks WHERE lib_key=$lib_key ";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_libs->blocks_in_lib($lib_key) - $sql " . mysqli_error($GLOBALS['link']));
$blocks = array();
while ($aux = mysqli_fetch_assoc($resultado)){
$blocks[$aux['block_key']] = $aux;}
mysqli_free_result($resultado);
return $blocks;}
public function lib_exists($lib_key){
$sql = 'SELECT lib_key FROM libs WHERE lib_key=' . $lib_key;
$resultado = mysqli_query($GLOBALS['link'], $sql) or die('ERROR al intentar contar la tabla Libs  - ');
$fila = mysqli_fetch_assoc($resultado);
return ($fila['lib_key']) ? true : false;}
public static function get($lib_key){
$resultado = mysqli_query($GLOBALS['link'], 'SELECT lib_key,lib_name,lib_url_alias,cat1_key,cat2_key,lib_title,lib_description,lib_keywords FROM libs WHERE lib_key=' . $lib_key) or die('ERROR get Lib');
return mysqli_fetch_assoc($resultado);}
public static function get_url_alias($lib_key, $full = 0){
$result = mysqli_query($GLOBALS['link'], 'SELECT lib_url_alias FROM libs WHERE lib_key=' . $lib_key) or die('ERROR al intentar obtener lib_url_alias');
$fila = mysqli_fetch_assoc($result);
return c_lib::aux_url_alias($lib_key, $fila['lib_url_alias'], $full);}
public static function aux_url_alias($lib_key, $lib_url_alias, $full = 0){
if ($full > 0){
$lib_url_alias = CECO_URL_ALIAS_BASE_LIBRARY . '/' . $lib_url_alias . CECO_URL_ALIAS_FINAL_LIBRARY . $lib_key;
if ($full == 2){
$lib_url_alias = CECO_URL_DOMINIO . '/' . $lib_url_alias;}}
return $lib_url_alias;}}
