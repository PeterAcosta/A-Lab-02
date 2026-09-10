<?php

// --- 00-function.php ---
function createH1Header($itle) {
    return '<h1>' . htmlspecialchars($itle, ENT_QUOTES, 'UTF-8') . '</h1>';
}

// --- 01-function.php ---
function sum(int|float $a, int|float $b): int|float {
	
    return $a + $b;
}

// --- 02-function.php ---
function multiplicar(int|float $numero1, int|float $numero2)
{
	return $numero1 * $numero2;
}

// --- f_arrays.php ---
# Archivo de Funciones relacionadas con los ARRAYS[] , iniciado el jueves 3 de octubre de 2008  

# 9.
# 8. 
# 7.
# 6.
# 5.
# 4.
# 3. array_to_javascritp( $array,$nombre_array_javascript,$primero=true )
# 2. print_r2($nombre_de_matriz,$nivel=0,$base_de_matriz='nada')
# 1.


#-------------------------------------------------------------------------------------------------------------
# Function name :
# Function Date :
# Parameters 	:
# Return		:
# Example      	:
#-------------------------------------------------------------------------------------------------------------
#-------------------------------------------------------------------------------------------------------------

#-------------------------------------------------------------------------------------------------------------3
# Function name : array_to_javascritp( $array,$nombre_array_javascript,$primero=true )
# Function Date :
# Parameters 	:
# Return        :
# Example      	:
#-------------------------------------------------------------------------------------------------------------
function array_to_javascritp($array, $nombre_array_javascript, $primero = true)             
{
    if ($primero)  echo PHP_EOL."var ";
    echo "$nombre_array_javascript = [] ;" . PHP_EOL;
    foreach ($array as $indice => $valor) {
        $indice = (is_string($indice)) ? '"' . $indice . '"' : $indice;
        if (is_array($valor)) {
            // $array2=array();
            $array2 = $valor;
            $nombre2 = $nombre_array_javascript . '[' . $indice . ']';
            array_to_javascritp($array2, $nombre2, false);
        } else {
            echo $nombre_array_javascript . '[' . $indice . ']="' . $valor . '";' . PHP_EOL;
        }
        #echo "$indice => $valor".chr(10);
    }
}

#-------------------------------------------------------------------------------------------------------------



# retirado de __source/PHP-functions/f_arrays.php
#-------------------------------------------------------------------------------------------------------------2
# Function name : print_r2
# Function Date : sabado 13 de diciembre de 2008
# Parameters 	: STRING : el nombre de una matriz ,   puede ser una matriz multidimensional
# Return		: no devuleve nada, imprime en pantalla directamente
# Example      	: print_r2('matriz')  funciona solo CON COMILLAS SIMPLES
# CUIDADO funciona solo con matrzes en el ambito GLOBAL
#-------------------------------------------------------------------------------------------------------------
function print_r2($nombre_de_matriz,$nivel=0,$base_de_matriz='nada'){
    if(substr($nombre_de_matriz,0,1)=='$')$nombre_de_matriz=substr($nombre_de_matriz,1,strlen($nombre_de_matriz)-1);
    global ${$nombre_de_matriz} ;
    $matriz=${$nombre_de_matriz};   	# aqui utilizo "variables dinamicas"  o "variables de variables"
    $separa='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    #$separa='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
    #echo '<style type="text/css">';
    #echo ' .print_r2 {font-family: Arial, Helvetica, sans-serif; font-size: 12px;color: #000000; } ';
    #echo '</style> ';
    echo '<div class="print_r2">';
    if(gettype($matriz)!='array'){
        echo '--ERROR-- $'.$nombre_de_matriz.' no es del tipo matriz valido <br />';
        echo 'gettype( $'.$nombre_de_matriz.' ) = '.gettype($matriz).'<br />';
        echo 'el valor de <strong> $'.$nombre_de_matriz.' = '.$matriz.'</strong><br />';
        return;
    }
    $base_de_matriz=($base_de_matriz=='nada')?$nombre_de_matriz:$base_de_matriz;
    foreach( $matriz as $indice => $valor){
        $key=(gettype($indice)=='string')?'\''.$indice.'\'':$indice;
        $val=(gettype($valor)=='string')?'\''.$valor.'\'':$valor;
        for ($i = 1 ; $i <= $nivel ; $i++) echo $separa ;
        echo '<strong>$'.$base_de_matriz.'[ '.$key.' ] =</strong> '.$val.' <br />';
        if(gettype($valor)=='array'){
            global $matriz_auxiliar;
            $matriz_auxiliar=$valor;
            print_r2('matriz_auxiliar',$nivel+1,$base_de_matriz.'[ '.$key.' ]');
        }
    }
    echo '</div>';
    return;
}

// --- f_functions.php ---
#-------------------------------------------------------------------------------------------------------------
# File name    : f_functions
# File created : 18-feb-2009
# File autor   : Peter Acosta
# File description :
#-------------------------------------------------------------------------------------------------------------
# 9.
# 8. 
# 7.
# 6.
# 5.
# 4. 
# 3. 
# 2. archivo_existe_comodin($pattern,$folder='')
# 1. byte_size($bytes)


#-------------------------------------------------------------------------------------------------------------
# Function name :
# Function Date :
# Parameters 	:
# Return		:
# Example      	:
#-------------------------------------------------------------------------------------------------------------
#-------------------------------------------------------------------------------------------------------------


#-------------------------------------------------------------------------------------------------------------2
# Function name : archivo_existe_comodin($pattern,$folder='')
# Function Date : Viernes 28 de agosto de 2009
# Parameters 	: STRing con un pattern a buscar y STRING con la ruta del directorio donde se desea buscar
# Return	: BOOL -> TRUE si existe algun archivo o FALSE si no existe ninguno que cumpla con el pattern
# Example      	: archivo_existe_comodin( '*.txt' , 'c:\garage' )
#-------------------------------------------------------------------------------------------------------------
function archivo_existe_comodin($pattern, $folder = '')
{
    if ($folder != '') {
        chdir($folder);
    }
    if (count(glob($pattern)) > 0) {
        return true;
    } else {
        return false;
    }
}
#-------------------------------------------------------------------------------------------------------------
#-------------------------------------------------------------------------------------------------------------1
# Function name : byte_size($bytes)
# Function Date : jueves 22 de enero de 2009
# Autor			: Peter Acosta
# Parameters 	: INT ; un numero de bytes del tamaño de algun archivo
# Return		: STRING , byte_size(20211982)   ->  '19.28 MB'
# Function 		: convierte los bytes en KB MB o GB segun sea el tamañno
#-------------------------------------------------------------------------------------------------------------
function byte_size($bytes)
{
    $size = $bytes / 1024;
    $size = number_format($size, 2);
    $size .= ' KB';
    /*
    if ($size < 1024) {
        $size = number_format($size, 2);
        $size .= ' KB';
    } else {
        if ($size / 1024 < 1024) {
            $size = number_format($size / 1024, 2);
            $size .= ' MB';
        } elseif ($size / 1024 / 1024 < 1024) {
            $size = number_format($size / 1024 / 1024, 2);
            $size .= ' GB';
        }
    }
    */
    return $size;
}


#-------------------------------------------------------------------------------------------------------------