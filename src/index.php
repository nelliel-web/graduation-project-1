<?php
require_once 'connect_db.php';
require_once 'functions.php';

$order = $_POST;

if (!empty($order['email']) and !empty($order['name']) and !empty($order['phone'])) {
    $email = $order['email'];

    $find_email = findGetEmail($pdo, $email);

    if (empty($find_email)) {
        addNewUser($pdo, $email, $order['name'], $order['phone']);
        addNewOrder($pdo, $email);
        echo $front_message = 'Спасибо - это ваш первый заказ!';
    } else {
        addNewOrder($pdo, $email, 1);
        $find_id = getIdByEmail($pdo, $email);
        $orders_by_id = getNumberOfAllOrders($pdo, $find_id);
        echo $front_message = "Спасибо! Это уже $orders_by_id заказ!";
    }

    $last_number_order = getLustOrder($pdo);
    $headers = 'From: webmaster@example.com;';
    sendMail($order, $last_number_order, $front_message, $headers);
}

$dbh = null;