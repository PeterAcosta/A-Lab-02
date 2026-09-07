<?php
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








