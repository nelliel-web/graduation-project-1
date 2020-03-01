<?php
require_once 'connect_db.php';

$order = $_POST;

if (!empty($order['email']) and !empty($order['name']) and !empty($order['phone'])) {
    $email = $order['email'];

    //Ищем в БД первый найденный email и присваеваем реузльтат в переменную $find_email
    $sth = $pdo->prepare("select `email` from users where `email` = :email LIMIT 1;");
    $sth->execute(['email' => $email]);
    $find_email = $sth->fetchAll(PDO::FETCH_ASSOC);


    if (empty($find_email)) {
        // Добавляем в таблицу users данные полученные из формы
        $sth = $pdo->prepare("INSERT INTO users SET `email` = :email, `name` = :name, `phone` = :phone");
        $sth->execute(['email' => $email, 'name' => $order['name'], 'phone' => $order['phone']]);

        // Добавляем в order в поле user_id - id из бд users где поле email равен введенному в форму email
        $sth = $pdo->prepare("INSERT INTO `order` SET `user_id` = (SELECT `id` FROM users WHERE `email` = :email);");
        $sth->execute(['email' => $email]);
        echo $front_message = 'Спасибо - это ваш первый заказ!';
    } else {
        // Добавляем в order в поле user_id - id из бд users где поле email равен введенному в форму email
        $sth = $pdo->prepare("INSERT INTO `order` SET `user_id` = (SELECT `id` FROM users WHERE `email` = :email LIMIT 1);");
        $sth->execute(['email' => $email]);


        // Получаем ID пользователя по email
        $sth = $pdo->prepare("select `id` from users where `email` = :email;");
        $sth->execute(['email' => $email]);
        $find_id = $sth->fetchAll(PDO::FETCH_ASSOC);
        $find_id = $find_id[0]['id'];

        // Получаем все заказы сделанные этим пользователем
        $sth = $pdo->query("select `user_id` from `order` where `user_id` = $find_id;");
        $orders_by_id = $sth->fetchAll(PDO::FETCH_ASSOC);
        $orders_by_id = count($orders_by_id);

        echo $front_message = "Спасибо! Это уже $orders_by_id заказ";
    }

    $sth = $pdo->query("SELECT max(id) FROM `order`;");
    $last_number_order = $sth->fetchAll(PDO::FETCH_ASSOC);
    $last_number_order = $last_number_order[0]['max(id)'];

    // Сообщение
    $message = "Заказ № $last_number_order\r\n\n" .
   "Ваш заказ будет доставлен по адресу: ул." .
        $order['street'] . ", дом " . $order['home'] . ", корпус " . $order['part'] . ", квартира " .
        $order['appt'] . ", этаж " . $order['floor'] . "\r\n\nВаш заказ: " . $order['comment'] . "\r\n\n"
        . $front_message;
    $to      = $order['email'];
    $subject = "Заказ в бургерной №$last_number_order";
    $headers = 'From: webmaster@example.com;';
    mail($to, $subject, $message, $headers);


}

$dbh = null;