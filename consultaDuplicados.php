<?php
SELECT `id_proveedor`, `codigo_b`, COUNT(*) FROM `productos` GROUP BY `codigo_b`, `id_proveedor` HAVING COUNT(*)>1 ORDER BY `id_proveedor`, `codigo_b` ASC

// USAR ESTA CONSULTA

SELECT `codigo_b`, COUNT(*)
FROM `productos`
WHERE `id_proveedor`=4 AND `estado`=1
GROUP BY `codigo_b`
HAVING COUNT(*) > 1
ORDER BY `codigo_b` ASC

// CONSULTA EN PHP
$sql = "SELECT `codigo_b`, COUNT(*) 
    FROM `productos`
    WHERE `id_proveedor`=4 AND 'estado'=1
    GROUP BY `codigo_b`
    HAVING COUNT(*) > 1
    ORDER BY `codigo_b` ASC";