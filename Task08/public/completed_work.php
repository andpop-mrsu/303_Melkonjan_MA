<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <title>Выполненые работы</title>
</head>

<body>
    <div class="nav_bar">
        <nav>
            <ul>
                <li>
                    <a href="./appointments.php">Запись на прием</a>
                </li>
                <li>
                    <a href="./index.php">Информация о мастерах</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php
    require_once "./config/connect.php";
    $query = "SELECT * FROM workers WHERE id == {$_GET['id']};";
    $name = getData(pdo: $pdo, query: $query);
    ?>
    <h2 style="text-align:center;">Имя мастера :
        "<?= $name[0]['second_n'], " ", $name[0]['first_n'], " ", $name[0]['middle_n'] ?>"</h2>
    <?php
    $query = "SELECT day,name_service,price,work_status FROM registrations
                  INNER JOIN services s on registrations.service_id = s.id WHERE worker_id == {$_GET['id']};";
    $data = getData(pdo: $pdo, query: $query);
    ?>
    <table border="1" cellpadding="10" class="table">
        <thead>
            <th>Дата</th>
            <th>Услуга</th>
            <th>Стоимость</th>
        </thead>
        <?php foreach ($data as $d) { ?>
            <tr>
                <th><?= $d['day'] ?></th>
                <th><?= $d['name_service'] ?></th>
                <th><?= $d['price'] ?></th>
            </tr>
        <?php } ?>
    </table>

</body>

</html>