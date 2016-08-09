<?php 
require_once("Database.php");

$db = new Database("seve", "localhost", "root", "");
$db->dbCreation();
$db->dbConnection();
$db->createTables();
$db->dbDisconnection();
?>

<!-- "^[a-zA-Z'.\\s]{1,40}$" -->