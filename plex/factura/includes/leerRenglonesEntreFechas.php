<?php
date_default_timezone_set('America/Argentina/Buenos_Aires');

// Realiza la conexion a PLEX
$con = mysql_connect('serversrl', 'consulta', 'readonly') or die(mysql_error());
//echo isset($con)? "$con OK":"$con NO SE REALIZO";
mysql_select_db('plex', $con) or die(mysql_error());

/**
 * Ingresar el codigo del producto a buscar
 * @var integer $codigo
 */
$codigo = 9999986541;
echo "-----------------------------------------<br>";
echo "Producto codigo: ".$codigo."<br>";
echo "-----------------------------------------<br>";
//--------------------------------

$fecha = date('Y-m-d');
$fecha_7 = date('Y-m-d', strtotime("-7 day"));
$fecha_14 = date('Y-m-d', strtotime("-14 day"));
$fecha_30 = date('Y-m-d', strtotime("-30 day"));
$fecha_3m = date('Y-m-d', strtotime("-3 month"));
$fecha_6m = date('Y-m-d', strtotime("-6 month"));
$fecha_1a = date('Y-m-d', strtotime("-1 year"));

$aFechas = array($fecha_7, $fecha_14, $fecha_30, $fecha_3m, $fecha_6m);

/*
var_dump($aFechas);
echo "<br>";
echo "Fecha de hoy: ".$fecha."<br>";
echo "Fecha 7 días antes: ".$fecha_7."<br>";
echo "Fecha 14 días antes: ".$fecha_14."<br>";
echo "Fecha 30 días antes: ".$fecha_30."<br>";
echo "Fecha 3 meses antes: ".$fecha_3m."<br>";
echo "Fecha 6 meses antes: ".$fecha_6m."<br>";
echo "Fecha 1 año antes: ".$fecha_1a."<br>";
*/
$hasta = $fecha;
$comienza = time();

foreach ($aFechas as $desde){
    echo "----- Fecha desde: ".$desde." -------<br>";
    // Busco los comprobantes entre fechas
/*    
    $sql = 'SELECT SUM(factlineas.Cantidad) AS total FROM factlineas 
                INNER JOIN factcabecera ON (factlineas.IDComprobante=factcabecera.IDComprobante)
                WHERE factlineas.IDProducto=9999986541 
                AND (factcabecera.Tipo="TF" OR factcabecera.Tipo="TK")'; 
                //AND factcabecera.Emision>'2016-10-27'
                //AND factcabecera.Emision<='2017-10-27'";
    
    echo $sql."<br>";
    echo "<br>";
    $result = mysql_query($sql, $con) or die(mysql_error());;
    $fila = mysqli_fetch_array($result);
    $numero_filas = mysqli_num_rows($result);
    echo "columnas: ".$numero_filas."<br>";
*/
    $query = "SELECT SUM(factlineas.Cantidad) AS cantidad FROM factlineas 
            INNER JOIN factcabecera ON (factlineas.IDComprobante = factcabecera.IDComprobante)
            WHERE factlineas.IDProducto = ".$codigo."
            AND factcabecera.Emision >'".$desde."'
            AND factcabecera.Emision <='".$hasta."'
            AND (factcabecera.Tipo='TF' OR factcabecera.Tipo='TK')";
    $result = mysql_query($query) or die(mysql_error());
    $fila = mysql_fetch_array($result);
    mysql_free_result($result);

    var_dump($fila);
    echo "### Cantidad -> ".$fila['cantidad']."<br>";

    $hasta = $desde;
} 
$finaliza = time();
$demora = $finaliza - $comienza;
echo "Consulta demoró -> ".$demora."<br>";

?>
