<?php 



$obj = json_decode($json);
$i = 1;
foreach($obj->objects as $objects){
echo $i.'__'.$objects->name;
echo '----';
echo   substr($objects->number, 2);

echo '<br>';
$i++;
}
?>