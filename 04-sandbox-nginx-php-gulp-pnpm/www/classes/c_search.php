<?php
class c_search{
public function guardar_en_central($action, $cadena){
if (strlen($cadena) < 2 && $cadena != 'x'){
return false;}
$cadena = substr($cadena, 0, 32);
$campo = ($action == 'search') ? 'search_times' : 'link_times';
$sql = "SELECT search_key FROM search_central WHERE search_keywords='$cadena'";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->guardar_en_central($action,$cadena) - $sql <br />" . mysqli_error($GLOBALS['link']));
if (mysqli_num_rows($resultado) == 0){
$valor = $this->cadena_value($cadena);
$dia = date("Ymd");
$sql = "INSERT INTO search_central SET search_keywords='$cadena' , $campo=1 , search_value=$valor , search_day='$dia'";
} else {
$row = mysqli_fetch_assoc($resultado);
$sql = "UPDATE search_central SET $campo=$campo+1 WHERE search_key={$row['search_key']}";}
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->guardar_en_central($action,$cadena) - $sql <br />" . mysqli_error($GLOBALS['link']));
return $resultado;}
public function today_a_central(){
$sql = "SELECT today_key , today_action , today_value FROM today WHERE today_done=0 AND (today_action='search' OR today_action='link')";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->today_a_central() - $sql <br />" . mysqli_error($GLOBALS['link']));
while ($row = mysqli_fetch_assoc($resultado)){
$respuesta = $this->guardar_en_central($row['today_action'], $row['today_value']);
if ($respuesta){
$sql = "UPDATE today SET today_done=1 WHERE today_key={$row['today_key']}";
$res2 = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->today_a_central() - $sql <br />" . mysqli_error($GLOBALS['link']));}}
$sql = "DELETE FROM today WHERE today_done=1 AND (today_action='search' OR today_action='link')";
$res3 = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->today_a_central() - $sql <br />" . mysqli_error($GLOBALS['link']));
return;}
public function central_a_top($cantidad){
$sql = 'TRUNCATE TABLE search_top';
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->central_a_top($cantidad) - $sql <br />" . mysqli_error($GLOBALS['link']));
$sql = "SELECT search_keywords, search_average FROM search_central ORDER BY search_average DESC LIMIT $cantidad";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->central_a_top($cantidad) - $sql <br />" . mysqli_error($GLOBALS['link']));
$ranking = 0;
while ($row = mysqli_fetch_assoc($resultado)){
$ranking++;
$keywords = $row['search_keywords'];
$promedio = $row['search_average'];
$cantidad = $this->cant_blocks_keyword($keywords);
$sql = "INSERT INTO search_top SET search_ranking=$ranking , search_keywords='$keywords' , search_average=$promedio, cant_blocks=$cantidad ";
$nada = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->central_a_top($cantidad) - $sql <br />" . mysqli_error($GLOBALS['link']));}
return;}
public function cadena_value($cadena){
$valor = count(explode(' ', $cadena));
return ($valor > 3) ? 3 : $valor;}
function calculos_diarios(){
$valor_del_search = 2;
$valor_del_link = 0.3;
$sql = 'UPDATE search_central SET';
$sql .= ' days_sum=DATEDIFF(CURRENT_DATE(),search_day)';
$sql .= ", search_points=((search_times*search_value*$valor_del_search)+(link_times*$valor_del_link))*search_coef";
$sql .= ', search_average=search_points/IF(days_sum>1,days_sum,3)';
$nada = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->calculos_diarios() - $sql <br />" . mysqli_error($GLOBALS['link']));
return;}
function lee_top($cantidad){
$sql = "SELECT search_ranking , search_keywords FROM search_top WHERE cant_blocks > 0 LIMIT $cantidad";
$resultado = mysqli_query($GLOBALS['link'], $sql) or die("<br />ERROR - c_search->lee_top($cantidad) - $sql <br />" . mysqli_error($GLOBALS['link']));
$top_search = array();
while ($row = mysqli_fetch_assoc($resultado)){
$top_search[$row['search_ranking']] = $row['search_keywords'];}
return $top_search;}
public function cant_blocks_keyword($keys){
$keys = trim($keys);
$sql = "SELECT COUNT(*) FROM blocks WHERE block_description LIKE '%$keys%'";
$resultado = mysqli_query($GLOBALS['link'], $sql);
$row = mysqli_fetch_row($resultado);
return intval($row[0]);}}
