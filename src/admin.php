<?php
require_once 'connect_db.php';
require_once 'functions.php';

?>
    <link rel="stylesheet" href="style.css">
    <h1>Список всех зарегистрированных пользователей</h1>
<?php
getUsersTable($pdo, DB_TABLE_USERS);
?>
    <h1>Список всех заказов</h1>
<?php
getOrdersTable($pdo, DB_TABLE_ORDERS);

$dbh = null;