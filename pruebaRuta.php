<?php
$server = $_SERVER['DOCUMENT_ROOT'];

echo "SERVER ->".$server." <br>";

$dir = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

echo "dir -->".$dir."<br>";

$aDir = explode("\\", $dir);

echo "----> ".$server."/".$aDir[1]." <br>";

?>
