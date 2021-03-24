<?php

try {
    $bdd = new PDO('mysql:host=localhost;dbname=test;charset=utf8', 'xxx', 'xxx');
} catch (Exception $e) {
    die('Erreur : '.$e->getMessage());
}

?>