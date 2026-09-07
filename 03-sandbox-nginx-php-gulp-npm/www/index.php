<?php

require '_functions.php';
$fecha = date('Y-m-d H:i:s');


echo createH1Header('01-sandbox-nginx-php-grunt');

echo "<a href='/index.html'>< < < Volver</a>";
echo "   $fecha: ";



phpinfo();