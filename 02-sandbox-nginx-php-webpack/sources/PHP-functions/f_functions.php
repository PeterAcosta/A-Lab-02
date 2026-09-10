<?php

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






