<?php
class c_cat2{
public function __construct(){
}
function actualiza(){
$sql = "SELECT cat2_key , COUNT(*) , SUM(view_total) , SUM(download_total) FROM blocks GROUP BY cat2_key";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - mycat2->actualiza() - $sql <br />" . mysqli_error($GLOBALS['link']));
while ($row = mysqli_fetch_assoc($resultado)){
$sql2 = "UPDATE cat2 SET cat2_total={$row['COUNT(*)']} , cat2_view={$row['SUM(view_total)']} , cat2_download={$row['SUM(download_total)']} WHERE cat2_key={$row['cat2_key']}";
$resultado2 = mysqli_query($GLOBALS['link'], $sql2) or die("<br />ERROR - mycat2->actualiza() - $sql2 <br />" . mysqli_error($GLOBALS['link']));}}
function addNew($cat1_key){
$palabra_nuevo = 'new';
$sql = 'SELECT cat2_key FROM cat2 WHERE cat2_name="' . $palabra_nuevo . '" ORDER BY cat2_key LIMIT 1';
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat2->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
$fila = mysqli_fetch_assoc($resultado);
$new_key2=0;
if ($fila){
$new_key2 = $fila['cat2_key'];
$sql = "UPDATE cat2 SET cat1_key=$cat1_key WHERE cat2_key=$new_key2";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat2->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
} else {
$sql = 'INSERT INTO cat2 SET cat2_name="' . $palabra_nuevo . '"';
$sql .= ',cat1_key=' . $cat1_key;
$sql .= ',cat2_day="' . date("Ymd") . '"';
mysqli_query($GLOBALS['link'], $sql) or die("ERROR - c_cat2->AddNew() - $sql " . mysqli_error($GLOBALS['link']));
$new_key2 = mysqli_insert_id($GLOBALS['link']);}
return $new_key2;}
public static function get_more_data($cat1_key,$cat2_key){
global $link, $CATs;
$sql = 'SELECT cat2_title ,cat2_description , cat2_keywords  FROM cat2 WHERE cat2_key=' . $cat2_key;
$resultado = mysqli_query($link, $sql) or die("ERROR - ($cat2_key) - $sql ");
return array_merge($CATs[$cat1_key]['cat2'][$cat2_key], mysqli_fetch_assoc($resultado));}
public static function get_cat2() : array{
global $link;
$cat2=array();
$sql = 'SELECT cat1_key , cat2_key , cat2_name , cat2_url_alias FROM cat2 ORDER BY cat2_name';
$resultado = mysqli_query($link, $sql) or exit('<br />ERROR - No puedo leer cat2' . mysqli_error($link));
while ($row = mysqli_fetch_assoc($resultado)){
$cat2 [$row['cat1_key']] [$row['cat2_key']] = array('name' => $row['cat2_name'], 'url_alias' => $row['cat2_url_alias']);}
mysqli_free_result($resultado);
return $cat2;}}
