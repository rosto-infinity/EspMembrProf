<?php
$dsn = 'mysql:host=127.0.0.1;dbname=espace_membres2';
$username = 'root';
$password = '';
$options = [];
try {
$bdd= new PDO($dsn, $username, $password, $options);
} catch(PDOException $e) {
die("erreur". $e->getMessage());
}
 ?>