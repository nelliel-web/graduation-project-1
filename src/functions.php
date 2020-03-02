<?php
function findIdByGetEmail(object $pdo, string $email)
{//Ищем в БД первый найденный email и присваеваем реузльтат в переменную $find_email
    $sth = $pdo->prepare("select `id` from users where `email` = :email;");
    $sth->execute(['email' => $email]);
    $find_id = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $find_id[0]['id'];
}

function addNewUser(object $pdo, string $email, string $name, string $phone)
{// Добавляем в таблицу users данные полученные из формы
    $sth = $pdo->prepare("INSERT INTO users SET `email` = :email, `name` = :name, `phone` = :phone");
    $sth->execute(['email' => $email, 'name' => $name, 'phone' => $phone]);
}

function addNewOrder(object $pdo, string $id, array $order)
{// Добавляем в orders в поле user_id - id из бд users где поле email равен введенному в форму email

    if ($order['payment'] == 'change') {
        $payment = 'Наличными. Потребуется сдача';
    } elseif ($order['payment'] == 'pay_cart') {
        $payment = 'Оплата по карте';
    } else {
        $payment = $order['payment'];
    }
    if (isset($order['callback']) and !empty($order['callback'])) {
        $callback = 'Не перезванивать';
    } else {
        $callback = 'Позвонить!';
    }
    if (isset($order['comment']) and !empty($order['comment'])) {
        $comment = $order['comment'];
    } else {
        $comment = '-';
    }
    $address = getLocation($order);
    if (empty($address)) {
        $address = '-';
    }

    $sth = $pdo->prepare("INSERT INTO `orders` SET `user_id` = $id, `pay` = '$payment', `call_back` = '$callback', `address` = :address, `comment` = :comment");
    $sth->execute(['address' => $address, 'comment' => $comment]);
}


function getNumberOfAllOrders(object $pdo, int $id)
{// Подсчитываем кол-во заказов, сделанных этим id users
    $sth = $pdo->query("select `user_id` from `orders` where `user_id` = $id;");
    $orders_by_id = $sth->fetchAll(PDO::FETCH_ASSOC);
    return (int)count($orders_by_id);
}

function getLustOrder(object $pdo)
{// Получаем ID последнего заказа
    $sth = $pdo->query("SELECT max(id) FROM `orders`;");
    $last_number_order = $sth->fetchAll(PDO::FETCH_ASSOC);
    return (int)$last_number_order[0]['max(id)'];
}

function sendMail(array $order, int $last_number_order, string $front_message, string $headers)
{// Отправляем сообщение
    $date = date('d.m.Y H:i');
    $comment = isset($order['comment']) ? $order['comment'] : '';


    $message = "Заказ № $last_number_order | $date\r\n\n" . "Ваш заказ будет доставлен по адресу:" .
        getLocation($order) . "\r\n\n" . $comment . $front_message;

    $to = escapeshellcmd($order['email']);
    $subject = "Заказ в бургерной №$last_number_order";

    mail($to, $subject, $message, $headers);
}

function getLocation($order)
{
    $street = !empty($order['street']) ? ' ул.' . escapeshellcmd($order['street']) : '';
    $home = !empty($order['home']) ? ', дом ' . escapeshellcmd($order['home']) : '';
    $part = !empty($order['part']) ? ', корпус ' . escapeshellcmd($order['part']) : '';
    $appt = !empty($order['appt']) ? ', квартира ' . escapeshellcmd($order['appt']) : '';
    $floor = !empty($order['floor']) ? ', этаж ' . escapeshellcmd($order['floor']) : '';
    return $street . $home . $part . $appt . $floor;
}

function getUsersTable(object $pdo, string $bd)
{
    $sth = $pdo->query("select * from `$bd` ORDER BY `id`;");
    $find_all_users = $sth->fetchAll(PDO::FETCH_ASSOC);
    echo '<table><tr><th>ID</th><th>E-mail</th><th>Имя</th><th>Телефон</th></tr>';
    foreach ($find_all_users as $user) {
        echo '<tr>';
        foreach ($user as $value) {
            echo "<td>$value</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
}

function getOrdersTable(object $pdo, string $bd)
{
    $sth = $pdo->query("select `id`,`pay`,`call_back`, `address`, `comment` from `$bd` ORDER BY `id` DESC;");
    $find_all_users = $sth->fetchAll(PDO::FETCH_ASSOC);
    echo '<table><tr><th>№ Заказа</th><th>Способ оплаты</th><th>Обратный звонок</th><th>Адрес</th><th>Комментарий</th></tr>';

    foreach ($find_all_users as $user) {
        echo '<tr>';
        foreach ($user as $value) {
            echo "<td>$value</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
}