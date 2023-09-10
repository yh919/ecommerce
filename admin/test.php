<?php


$stmt = $con->prepare("SELECT * FROM items");

$stmt->execute();

$row = $stmt->fetchAll();

echo '<pre>';
print_r($rows);
echo '</pre>';

?>