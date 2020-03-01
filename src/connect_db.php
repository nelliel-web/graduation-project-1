<?php
require_once 'config.php';
try {
    $pdo = new PDO(DSN, DB_USER, DB_PASS);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}