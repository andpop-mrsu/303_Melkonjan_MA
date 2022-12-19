<?php
function column_alignment($array)
{
    $amount_symbol_str = array();
    foreach ($array as $a) {
        $amount_symbol_str[] = iconv_strlen($a);
    }

    $max_str = max($amount_symbol_str);

    $temp = array();
    foreach ($array as $d) {
        $a = abs($max_str - iconv_strlen($d));
        $temp[] = str_repeat(" ", $a) . $d;
    }

    $temp[] = $max_str;

    return $temp;
}


$pdo = new PDO("sqlite:data.db");


$query = "SELECT workers.id,second_n,first_n,middle_n,DATE(registrations.day,'unixepoch') AS 'date',name_service,price FROM workers
                INNER JOIN registrations on registrations.worker_id == workers.id
                INNER JOIN services s on registrations.service_id = s.id
                ORDER BY second_n,'date';";

$statement = $pdo->query($query);
$data = $statement->fetchAll();
$statement->closeCursor();

$array_id = [];

foreach ($data as $d) {
    $array_id[] = $d['id'];
}

$array_id = array_unique($array_id);
sort($array_id);
$array_id = column_alignment($array_id);

print_r("╔═══════════════════════════" . str_repeat('═', $array_id[count($array_id) - 1] - 1) . "═╗\n");
print_r("║ Выберите номер мастера :   " . str_repeat(' ', $array_id[count($array_id) - 1] - 1) . "║\n");
print_r("╠════════════════════════════" . str_repeat('═', $array_id[count($array_id) - 1] - 1) . "╣\n");
for ($i = 0; $i < count($array_id) - 1; ++$i) {
    print_r("║      {$array_id[$i]}                     ║\n");
}
print_r("╚════════════════════════════" . str_repeat('═', $array_id[count($array_id) - 1] - 1) . "╝\n");

print_r("Input : ");
$worker_id = readline();

if (($worker_id > max($array_id) && $worker_id < 0) || ((float)$worker_id * 10) % 10 !== 0) {
    print_r("Мастера с номером {$worker_id} в базе нету.\n");
    return -1;
}

if ($worker_id !== "") {
    $query = "SELECT workers.id,second_n,first_n,middle_n,DATE(registrations.day,'unixepoch') AS 'date',name_service,price FROM workers
                    INNER JOIN registrations on registrations.worker_id == workers.id
                    INNER JOIN services s on registrations.service_id = s.id WHERE worker_id == {$worker_id}
                    ORDER BY second_n,'date';";
    $statement = $pdo->query($query);
    $data = $statement->fetchAll();
}

$array_full_name = array();
$array_id = array();
$array_day = array();
$array_services = array();
$array_prices = array();


foreach ($data as $d) {
    $array_full_name[] = $d['second_n'] . " " . $d['first_n'] . " " . $d['middle_n'];
    $array_day[] = $d['date'];
    $array_id[] = $d['id'];
    $array_services[] = $d['name_service'];
    $array_prices[] = $d['price'];
}

$array_full_name = column_alignment($array_full_name);
$array_prices = column_alignment($array_prices);
$array_id = column_alignment($array_id);
$array_services = column_alignment($array_services);
$array_day = column_alignment($array_day);


print_r("╔══" . str_repeat('═', $array_id[count($array_id) - 1]) . "══╦═══" . str_repeat('═', $array_full_name[count($array_id) - 1]) . "═╦══" . str_repeat('═', $array_day[count($array_id) - 1]) . "══╦══" . str_repeat('═', $array_services[count($array_id) - 1]) . "══╦══" . str_repeat('═', $array_prices[count($array_id) - 1]) . "══╗\n"); //  ╚╔ ╩ ╦ ╠ ═ ╬ ╣ ║ ╗ ╝
print_r("║ " . str_repeat(' ', $array_id[count($array_id) - 1] - 1) . "ID  ║   ФИО" . str_repeat(' ', $array_full_name[count($array_id) - 1] - 2) . "║  день" . str_repeat(' ', $array_day[count($array_id) - 1] - 2) . "║  услуга" . str_repeat(' ', $array_services[count($array_id) - 1] - 4) . "║  цена" . str_repeat(' ', $array_prices[count($array_id) - 1] - 2) . "║\n");
print_r("╠══" . str_repeat('═', $array_id[count($array_id) - 1]) . "══╬═══" . str_repeat('═', $array_full_name[count($array_id) - 1]) . "═╬══" . str_repeat('═', $array_day[count($array_id) - 1]) . "══╬══" . str_repeat('═', $array_services[count($array_id) - 1]) . "══╬══" . str_repeat('═', $array_prices[count($array_id) - 1]) . "══╣\n");
for ($i = 0; $i < count($array_prices) - 1; ++$i) {
    $format = "║  %d  ║  %s  ║  %s  ║  %s  ║  %s  ║\n";
    echo sprintf($format, $array_id[$i], $array_full_name[$i], $array_day[$i], $array_services[$i], $array_prices[$i]); //,$d['date'],$d['name_service'],$d["price"]);
}
print_r("╚══" . str_repeat('═', $array_id[count($array_id) - 1]) . "══╩═══" . str_repeat('═', $array_full_name[count($array_id) - 1]) . "═╩══" . str_repeat('═', $array_day[count($array_id) - 1]) . "══╩══" . str_repeat('═', $array_services[count($array_id) - 1]) . "══╩══" . str_repeat('═', $array_prices[count($array_id) - 1]) . "══╝\n");