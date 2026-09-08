<?php


if ( !isset($_GET['action']) OR $_GET['action'] !='swap' ) {

    die('bye bye...');

}






if (isset($_COOKIE['ceco_debug'])) {
    ## La SI cookie existe
    # echo "La cookie 'ceco_debug' existe, debo BORRARLA";
    unset($_COOKIE['ceco_debug']); // Elimina la cookie del arreglo $_COOKIE
    setcookie('ceco_debug', '', time() - 3600, '/'); // Establece una cookie con fecha de expiración en el pasado

} else {
    ## La NO cookie existe
    # echo "La cookie 'ceco_debug' no existe, debo CREARLA ";
    $duracion = time() + ( 60 * 60 * 24); // Duración de un dia)
    setcookie('ceco_debug','ON', $duracion, '/');

}



/*
echo '<pre>$_GET : ' . print_r($_GET, true) . '</pre>';
echo '<pre>$_POST : ' . print_r($_POST, true) . '</pre>';
echo '<pre>' . print_r($_SESSION, true) . '</pre>';
echo 'HTTP_REFERER : '.$_SERVER['HTTP_REFERER'];
exit;
*/

#header('Location: '. $_POST['pagina_origen']);
header('Location: ' . $_SERVER['HTTP_REFERER']);