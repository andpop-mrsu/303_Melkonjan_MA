<?php
require_once './config/connect.php';

if (!empty($_POST['specialization'])) {
    $query = "SELECT id FROM specializations WHERE specialization == '{$_POST["specialization"]}';";
    $data = getData(pdo: $pdo, query: $query);
    $query = "UPDATE workers SET first_n = '{$_POST['first_n']}', second_n='{$_POST['$second_n']}',
                  middle_n = '{$_POST['middle_n']}', specialization_id='{$data[0]["id"]}' WHERE id = {$_GET['worker_id']};";
    $pdo->exec($query);
    var_dump('test');
    header('Location: ./index.php');
} else if (explode('?', explode('/', $_SERVER["HTTP_REFERER"])[5])[0] === 'update_page.php') {
    var_dump('test');
    try {
        $query = "SELECT id FROM day_id WHERE day ='{$_POST['day']}';";
        $id = getData(pdo: $pdo, query: $query);
        $query = "UPDATE time_table SET day_id='{$id[0][0]}',time='{$_POST['time']}' WHERE id={$_GET["id"]}";
        $pdo->exec($query);
    } catch (Exception $e) {
        echo $e->getMessage();
    }
    header("Location: ./time_table.php?id={$_GET['worker_id']}");
}
