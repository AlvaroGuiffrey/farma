<?php
// abre BASE KELLERHOFF // en CSV

$archivo = fopen ("/var/www/html/farma/plex/FarmaciaMySql/productos.csv","r");
echo "ABRI BASE DE DATOS PRODUCTOS DE PLEX</br>";

$cont = 0;
while ($data = fgetcsv ($archivo, 1000, ",")) {
	$cont++;
	if ($data[9]==''){
		$codBarra = 0;
	}else{
		$codBarra = $data[9];
	}
	echo $cont." - ".$data[0]." - ".$codBarra." - ".$data[10]."<br>";

}
?>