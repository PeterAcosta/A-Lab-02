<?php
class c_cat1{
public function __construct(){
}
public function actualiza(){
$sql = "SELECT cat1_key , COUNT(*) , SUM(view_total) , SUM(download_total) FROM blocks GROUP BY cat1_key";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - mycat1->actualiza() - $sql <br />" . mysqli_error($GLOBALS['link']));
while ($row = mysqli_fetch_assoc($resultado)){
$sql2 = "UPDATE cat1 SET cat1_total={$row['COUNT(*)']} , cat1_view={$row['SUM(view_total)']} , cat1_download={$row['SUM(download_total)']} WHERE cat1_key={$row['cat1_key']}";
$resultado2 = mysqli_query($GLOBALS['link'], $sql2) or die("<br />ERROR - mycat1->actualiza() - $sql2 <br />" . mysqli_error($GLOBALS['link']));}}
public function addNew(){
$palabra_nuevo = 'NEW';
$sql = 'SELECT cat1_key FROM cat1 WHERE cat1_name="' . $palabra_nuevo . '" ORDER BY cat1_key LIMIT 1';
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat1->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
$fila = mysqli_fetch_array($resultado);
if ($fila){
$new_key1 = $fila['cat1_key'];
} else {
$sql = 'INSERT INTO cat1 SET cat1_name="' . $palabra_nuevo . '"';
$sql .= ',cat1_day="' . date("Ymd") . '"';
mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat1->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
$new_key1 = mysqli_insert_id($GLOBALS['link']);}
return $new_key1;}
public static function get_more_data($cat1_key){
global $link, $CATs;
$sql = 'SELECT cat1_title ,cat1_description , cat1_keywords  FROM cat1 WHERE cat1_key=' . $cat1_key;
$resultado = mysqli_query($link, $sql) or die("ERROR - c_cat1->get_cat1_keywords($cat1_key) - $sql ");
return array_merge($CATs[$cat1_key], mysqli_fetch_assoc($resultado));}
public static function get_cat1_cat2_basic($cached = true){
$token = 'cat1-cat2-get-all';
if ($cached){
$t0 = microtime(true);
$c = c_cacher::array_load($token);
$t1 = microtime(true);
$_SESSION['time']['11_cat_cache'] = round($t1 - $t0, 4);
} else {
$c = false;}
if (!$c){
$t0 = microtime(true);
global $link;
$sql = 'SELECT t1.cat1_key , t1.cat1_name , t1.cat1_url_alias , t2.cat2_key , t2.cat2_name , t2.cat2_url_alias
FROM cat1 AS t1 LEFT JOIN cat2 AS t2 ON t1.cat1_key = t2.cat1_key
ORDER BY t1.cat1_name , t2.cat2_name ';
$result = mysqli_query($link, $sql) or exit('<br />ERROR - No puedo leer cat1' . mysqli_error($link));
$c = array();
while ($row = mysqli_fetch_assoc($result)){
$c[$row['cat1_key']]['name'] = $row['cat1_name'];
$c[$row['cat1_key']]['url_alias'] = $row['cat1_url_alias'];
if (!is_null($row['cat2_key'])){
$c[$row['cat1_key']]['cat2'][$row['cat2_key']] = array('name' => $row['cat2_name'], 'url_alias' => $row['cat2_url_alias']);}}
$t1 = microtime(true);
$_SESSION['time']['10_cat_db'] = round($t1 - $t0, 4);
$c[6]['main_title'] = 'autocad-architecture-drawings-blocks-templates-dwg-dxf.png';
$c[3]['main_title'] = 'autocad-bathrooms-detail-drawings-blocks-templates-dwg-dxf.png';
$c[14]['main_title'] = 'autocad-decorative-elements-drawings-blocks-dwg-dxf.png';
$c[13]['main_title'] = 'autocad-equipment-drawings-blocks-dwg-dxf.png';
$c[13]['cat2'][10]['main_title'] = 'autocad-sports-gym-drawings-blocks-templates-dwg-dxf.png';
$c[7]['main_title'] = 'autocad-furniture-blocks-drawings-templates-dwg-dxf.png';
$c[4]['main_title'] = 'autocad-landscaping-blocks-drawings-templates-dwg-dxf.png';
$c[4]['cat2'][9]['main_title'] = 'autocad-plants-brushes-drawings-blocks-templates-dwg-dxf.png';
$c[4]['cat2'][8]['main_title'] = 'autocad-trees-drawings-blocks-templates-dwg-dxf.png';
$c[1]['main_title'] = 'people-autocad-blocks-drawings-templates-dwg-dxf.png';
$c[1]['cat2'][1]['main_title'] = 'autocad-man-men-people-blocks-drawings-templates-dwg-dxf.png';
$c[1]['cat2'][2]['main_title'] = 'autocad-women-woman-drawings-blocks-templates-dwg-dxf.png';
$c[1]['cat2'][4]['main_title'] = 'autocad-kids-family-children-groups-of-persons-drawings-blocks-dwg-dxf.png';
$c[2]['main_title'] = 'autocad-drawings-blocks-templates-symbols-dwg-dxf.png';
$c[2]['cat2'][3]['main_title'] = 'north-arrows-autocad-blocks-drawings-templates-dwg-dxf.png';
$c[5]['main_title'] = 'autocad-vehicles-drawings-blocks-templates-dwg-dxf.png';
$c[5]['cat2'][7]['main_title'] = 'autocad-aircraft-drawings-blocks-templates-dwg-dxf.png';
$c[5]['cat2'][5]['main_title'] = 'autocad-cars-motor-vehicles-drawings-blocks-dwg-dxf.png';
$c[5]['cat2'][6]['main_title'] = 'autocad-ships-boats-drawings-blocks-templates-dwg-dxf.png';
$c[5]['cat2'][11]['main_title'] = 'autocad-bikes-motorcycles-bicycles-drawings-blocks-dwg-dxf.png';
c_cacher::array_save($token, $c);}
return $c;}}
