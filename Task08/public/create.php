<?php

require_once './config/connect.php';

$first_n = $_POST['first_n'];
$second_n = $_POST['second_n'];
$middle_n = $_POST['middle_n'];
$specialization = $_POST['specialization'];

var_dump(explode('?', explode('/', $_SERVER["HTTP_REFERER"])[5])[0]);

if (explode('?', explode('/', $_SERVER["HTTP_REFERER"])[5])[0] === 'index.php') {
    $query = "INSERT INTO workers (first_n, second_n, middle_n, status, percent, specialization_id)
                    VALUES ('{$first_n}','{$second_n}','{$middle_n}','working',5,{$specialization});";
    $pdo->exec($query);
    header('Location: ./index.php');
} elseif (explode('?', explode('/', $_SERVER["HTTP_REFERER"])[5])[0] === 'time_table.php') {
    try {
        $query = "SELECT id FROM day_id WHERE day = '{$_POST['day']}'";
        $day = getData(pdo: $pdo, query: $query);
        $query = "INSERT INTO time_table (worker_id,day_id,time) VALUES ({$_GET['id']},'{$day[0][0]}','{$_POST['time']}');";
        $pdo->exec($query);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    header("Location: ./time_table.php?id={$_GET['id']}");
} else if (explode('?', explode('/', $_SERVER["HTTP_REFERER"])[5])[0] == 'appointments.php') {
    try {
        $query = "INSERT INTO registrations (worker_id,service_id,day) VALUES ({$_GET['worker_id']},{$_GET['service_id']},'{$_GET['day']}');";
        $pdo->exec($query);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    header("Location: ./appointments.php");
}
