<?php
$pdo = new PDO("sqlite:../data/data.db");

function getData($pdo, $query)
{
    $statement = $pdo->query($query);
    $data = $statement->fetchAll();
    $statement->closeCursor();
    return $data;
}