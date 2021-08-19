<?php
echo "IP de la máquina que usamos<br>";
$nombreHost = gethostbyaddr($_SERVER['REMOTE_ADDR']);
echo $nombreHost."<br>";
// echo $gethostname()."<br>"; no funciona
echo "SERVER DATOS<br>";
$nombreServer = $_SERVER['HTTP_HOST'];
echo $nombreServer."<br>";
echo "URL <br>";
$url = $_SERVER['REQUEST_URI'];
echo $url."<br>";
echo $_SERVER['PHP_SELF'];
