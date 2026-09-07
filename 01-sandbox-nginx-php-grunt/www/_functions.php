<?php /*! test.local - v1.0 - 07-09-2026 */

// --------------------------------------------------------------- source: _auxiliar/zGrunt/PHP-functions/function-00.php
function createH1Header($itle) {
return '<h1>' . htmlspecialchars($itle, ENT_QUOTES, 'UTF-8') . '</h1>';
}


// --------------------------------------------------------------- source: _auxiliar/zGrunt/PHP-functions/function-01.php
function sum(int|float $a, int|float $b): int|float {
return $a + $b;
}


// --------------------------------------------------------------- source: _auxiliar/zGrunt/PHP-functions/function-02.php
function multiplicar(int|float $numero1, int|float $numero2)
{
return $numero1 * $numero2;
}
/* este es el pie */