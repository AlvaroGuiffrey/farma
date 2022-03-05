<?php
$hostName = gethostname();
echo "HOST NAME -> ".$hostName."<br>";

$hostIp = gethostbyname($hostName);
echo "IP DEL HOST -> ".$hostIp."<br>";

$server = $_SERVER['DOCUMENT_ROOT'];

echo "SERVER -> ".$server." <br>";

$dir = substr(__DIR__, strlen($_SERVER['DOCUMENT_ROOT']));

echo "dir --> ".$dir."<br>";

$aDir = explode("\\", $dir);

echo "----> ".$server."/".$aDir[1]." <br>";

echo "....> ".$hostIp."/".$aDir[1]." <br>";



?>
