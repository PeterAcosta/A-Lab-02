<?php
class c_cacher{
public static function start_ob_cache(){
if (CECO_CACHE_ON) ob_start();}
public static function end_ob_cache($token){
if (CECO_CACHE_ON){
file_put_contents(
CECO_CACHE_FOLDER . 'ob-cache--' . $token . '.inc',
PHP_EOL . '<!-- Cached ' . date('Y-m-d H:i:s') . ' -->' . PHP_EOL . ob_get_contents() . PHP_EOL . '<!-- cached -->' . PHP_EOL);
ob_end_flush();}}
public static function include_cached($token){
if (CECO_CACHE_ON){
$file_name = CECO_CACHE_FOLDER . 'ob-cache--' . $token . '.inc';
if (file_exists($file_name)){
$included = include($file_name);
} else {
$included = false;}} else {
$included = false;}
return $included;}
public static function array_save($token, $my_array){
if (CECO_CACHE_ON){
$file_name = self::array_file_name($token);
$content = '<?php' . PHP_EOL . '' . PHP_EOL;
$content .= ' return ' . var_export($my_array, true) . ';';
file_put_contents($file_name, $content);}}
public static function array_load($token){
if (CECO_CACHE_ON){
$file_name = self::array_file_name($token);
if (file_exists($file_name)){
return include($file_name);
} else {
return array();}} else {
return array();}}
public static function array_file_name($token){
return CECO_CACHE_FOLDER . 'array--' . $token . '.inc';}}