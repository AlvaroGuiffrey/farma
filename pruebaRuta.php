<?php
$server = $_SERVER['DOCUMENT_ROOT'];

$dir = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

$aDir = explode("\\", $dir);

echo "----> ".$server."/".$aDir[1]." <br>";

?>