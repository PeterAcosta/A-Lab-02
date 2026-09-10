<?php

// //////////////////// 00-function.php ---
function createH1Header($itle) { return '<h1>' . htmlspecialchars($itle, ENT_QUOTES, 'UTF-8') . '</h1>'; }

// //////////////////// 01-function.php ---
function sum(int|float $a, int|float $b): int|float { return $a + $b; }

// //////////////////// 02-function.php ---
function multiplicar(int|float $numero1, int|float $numero2) { return $numero1 * $numero2; }

// //////////////////// f_arrays.php ---
function array_to_javascritp($array, $nombre_array_javascript, $primero = true) { if ($primero) echo PHP_EOL."var "; echo "$nombre_array_javascript = [] ;" . PHP_EOL; foreach ($array as $indice => $valor) { $indice = (is_string($indice)) ? '"' . $indice . '"' : $indice; if (is_array($valor)) { $array2 = $valor; $nombre2 = $nombre_array_javascript . '[' . $indice . ']'; array_to_javascritp($array2, $nombre2, false); } else { echo $nombre_array_javascript . '[' . $indice . ']="' . $valor . '";' . PHP_EOL; } } } function print_r2($nombre_de_matriz,$nivel=0,$base_de_matriz='nada'){ if(substr($nombre_de_matriz,0,1)=='$')$nombre_de_matriz=substr($nombre_de_matriz,1,strlen($nombre_de_matriz)-1); global ${$nombre_de_matriz} ; $matriz=${$nombre_de_matriz}; $separa='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; echo '<div class="print_r2">'; if(gettype($matriz)!='array'){ echo '--ERROR-- $'.$nombre_de_matriz.' no es del tipo matriz valido <br />'; echo 'gettype( $'.$nombre_de_matriz.' ) = '.gettype($matriz).'<br />'; echo 'el valor de <strong> $'.$nombre_de_matriz.' = '.$matriz.'</strong><br />'; return; } $base_de_matriz=($base_de_matriz=='nada')?$nombre_de_matriz:$base_de_matriz; foreach( $matriz as $indice => $valor){ $key=(gettype($indice)=='string')?'\''.$indice.'\'':$indice; $val=(gettype($valor)=='string')?'\''.$valor.'\'':$valor; for ($i = 1 ; $i <= $nivel ; $i++) echo $separa ; echo '<strong>$'.$base_de_matriz.'[ '.$key.' ] =</strong> '.$val.' <br />'; if(gettype($valor)=='array'){ global $matriz_auxiliar; $matriz_auxiliar=$valor; print_r2('matriz_auxiliar',$nivel+1,$base_de_matriz.'[ '.$key.' ]'); } } echo '</div>'; return; }

// //////////////////// f_functions.php ---
function archivo_existe_comodin($pattern, $folder = '') { if ($folder != '') { chdir($folder); } if (count(glob($pattern)) > 0) { return true; } else { return false; } } function byte_size($bytes) { $size = $bytes / 1024; $size = number_format($size, 2); $size .= ' KB'; return $size; }