<?php
$mysqli = new mysqli('serversrl', 'consulta', 'readonly', 'plex');

if($mysqli->connect_errno){
   echo "NO SE REALIZO coneccion<br>";
   echo $mysqli->connect_errno."<br";
   die('Error: '.$mysqli->connect_error);
}else{
	echo "OK coneccion<br>";
}


echo "Voy a ver la base de dato seleccionada<br>";

/* devuelve el nombre de la base de datos actualmente seleccionada */
if ($result = $mysqli->query("SELECT DATABASE()")) {
    $row = $result->fetch_row();
    printf("Default database is %s.\n", $row[0]);
    $mysqli->close();
    exit();
} else {
    echo "NO TUVO RESULTADO<br>";
}

?>
