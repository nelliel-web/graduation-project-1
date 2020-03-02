<?php
require_once 'connect_db.php';
require_once 'functions.php';

$order = $_POST;

if (!isset($order['payment'])) {
    $order['payment'] = 'Не указано';
}
if (!isset($order['callback'])) {
    $order['callback'] = '';
}
if (!isset($order['comment'])) {
    $order['comment'] = '';
}
$address = getLocation($order);

if (!empty($order['email'])) {

    if (empty(findIdByGetEmail($pdo, $order['email']))) {
        addNewUser($pdo, $order['email'], $order['name'], $order['phone']);
        addNewOrder($pdo, findIdByGetEmail($pdo, $order['email']), $order);
        echo $front_message = 'Спасибо - это ваш первый заказ!';
    } else {
        addNewOrder($pdo, findIdByGetEmail($pdo, $order['email']), $order);
        $orders_by_id = getNumberOfAllOrders($pdo, findIdByGetEmail($pdo, $order['email']));
        echo $front_message = "Спасибо! Это уже $orders_by_id заказ!";
    }

    $last_number_order = getLustOrder($pdo);
    $headers = 'From: webmaster@example.com;';
    sendMail($order, $last_number_order, $front_message, $headers);
}

$dbh = null;