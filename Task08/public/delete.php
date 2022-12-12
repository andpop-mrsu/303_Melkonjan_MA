<?php

require_once './config/connect.php';

if (explode('?', explode('/', $_SERVER["HTTP_REFERER"])[5])[0] === 'index.php') {
    $query = "UPDATE workers SET status='fired' WHERE id == {$_GET['id']}";
    $pdo->exec($query);
    header('Location: ./index.php');
}

if (explode('?', explode('/', $_SERVER["HTTP_REFERER"])[5])[0] === 'time_table.php') {
    $query = "DELETE FROM time_table WHERE id == {$_GET["data_id"]};";
    $pdo->exec($query);
    header("Location: ./time_table.php?id={$_GET["id"]}");
}
