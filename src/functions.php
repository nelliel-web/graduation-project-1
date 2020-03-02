<?php
function findGetEmail(object $pdo, string $email)
{//Ищем в БД первый найденный email и присваеваем реузльтат в переменную $find_email
    $sth = $pdo->prepare("select `email` from users where `email` = :email LIMIT 1;");
    $sth->execute(['email' => $email]);
    $find_email = $sth->fetchAll(PDO::FETCH_ASSOC);
    return $find_email;
}

function addNewUser(object $pdo, string $email, string $name, string $phone)
{// Добавляем в таблицу users данные полученные из формы
    $sth = $pdo->prepare("INSERT INTO users SET `email` = :email, `name` = :name, `phone` = :phone");
    $sth->execute(['email' => $email, 'name' => $name, 'phone' => $phone]);
}

function addNewOrder(object $pdo, string $email, int $limit = 0)
{// Добавляем в orders в поле user_id - id из бд users где поле email равен введенному в форму email
    if ($limit <= 0) {
        $sth = $pdo->prepare("INSERT INTO `orders` SET `user_id` = (SELECT `id` FROM users WHERE `email` = :email);");
    } else {
        $sth = $pdo->prepare("INSERT INTO `orders` SET `user_id` = (SELECT `id` FROM users WHERE `email` = :email LIMIT $limit);");
    }
    $sth->execute(['email' => $email]);
}

function getIdByEmail(object $pdo, string $email)
{// получаем ID пользователя по email
    $sth = $pdo->prepare("select `id` from users where `email` = :email;");
    $sth->execute(['email' => $email]);
    $find_id = $sth->fetchAll(PDO::FETCH_ASSOC);
    $find_id = $find_id[0]['id'];
    return (int)$find_id;
}

function getNumberOfAllOrders(object $pdo, int $id)
{// Подсчитываем кол-во заказов, сделанных этим id users
    $sth = $pdo->query("select `user_id` from `orders` where `user_id` = $id;");
    $orders_by_id = $sth->fetchAll(PDO::FETCH_ASSOC);
    $orders_by_id = count($orders_by_id);
    return (int)$orders_by_id;
}

function getLustOrder(object $pdo)
{// Получаем ID последнего заказа
    $sth = $pdo->query("SELECT max(id) FROM `orders`;");
    $last_number_order = $sth->fetchAll(PDO::FETCH_ASSOC);
    $last_number_order = $last_number_order[0]['max(id)'];
    return (int)$last_number_order;
}

function sendMail(array $order, int $last_number_order, string $front_message, string $headers)
{// Отправляем сообщение
    $date = date('d.m.Y H:i');
    $street = !empty($order['street']) ? ' ул.' . escapeshellcmd($order['street']) : '';
    $home = !empty($order['home']) ? ', дом ' . escapeshellcmd($order['home']) : '';
    $part = !empty($order['part']) ? ', корпус ' . escapeshellcmd($order['part']) : '';
    $appt = !empty($order['appt']) ? ', квартира ' . escapeshellcmd($order['appt']) : '';
    $floor = !empty($order['floor']) ? ', этаж ' . escapeshellcmd($order['floor']) : '';
    $comment = !empty($order['comment']) ? 'Ваш заказ: ' . escapeshellcmd($order['comment']) . "\r\n\n" : '';

    $message = "Заказ № $last_number_order | $date\r\n\n" . "Ваш заказ будет доставлен по адресу:" .
        $street . $home . $part . $appt . $floor . "\r\n\n" . $comment . $front_message;

    $to = escapeshellcmd($order['email']);
    $subject = "Заказ в бургерной №$last_number_order";

    mail($to, $subject, $message, $headers);
}

function getAllTable(object $pdo, string $bd, string $db_name)
{
    $sth = $pdo->query("SELECT `COLUMN_NAME` FROM `INFORMATION_SCHEMA`.`COLUMNS` WHERE `TABLE_SCHEMA`='$db_name' AND `TABLE_NAME`='$bd';");
    $find_columns = $sth->fetchAll(PDO::FETCH_ASSOC);

    $sth = $pdo->query("select * from `$bd` ORDER BY `id`;");
    $find_all_users = $sth->fetchAll(PDO::FETCH_ASSOC);
    echo '<table>';

    echo '<tr>';
    foreach ($find_columns as $column) {
        foreach ($column as $value) {
            echo "<th>$value</th>";
        }
    }
    echo '</tr>';

    foreach ($find_all_users as $user) {
        echo '<tr>';
        foreach ($user as $value) {
            echo "<td>$value</td>";
        }
        echo '</tr>';
    }
    echo '</table>';
}