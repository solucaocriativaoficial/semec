<?php
include_once(".". DIRECTORY_SEPARATOR ."class". DIRECTORY_SEPARATOR ."LoadClass.php");

$id = $_GET['id'];
$table = $_GET["tbl"];


$delete = new ConnectionDatabase();
$comand = "DELETE FROM $table WHERE id=:id"; 

$return = $delete->delete(
    $comand,
    array(
        "id" => $id
    )
);

echo $return;
?>