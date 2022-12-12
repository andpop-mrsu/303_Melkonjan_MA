<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./styles.css">
    <title>График</title>
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
    <h2>Рабочий график : "<?= $name[0]['second_n'], " ", $name[0]['first_n'], " ", $name[0]['middle_n'] ?>"</h2>
    <?php
    $query = "SELECT * FROM time_table WHERE worker_id == {$_GET['id']};";
    $data = getData(pdo: $pdo, query: $query);

    $query = "SELECT * FROM day_id;";
    $week_day = getData(pdo: $pdo, query: $query);

    ?>
    <table border="1" width="100%" cellpadding="10" class="table">
        <thead>
            <th>День недели</th>
            <th>Рабочее время</th>
            <th>Update</th>
            <th>Delete</th>
        </thead>
        <?php
        foreach ($data as $d) {
        ?>
            <tr>
                <th>
                    <?php
                    $query = "SELECT day FROM day_id WHERE id = {$d['day_id']};";
                    $day = getData(pdo: $pdo, query: $query);
                    print_r($day[0][0]);
                    ?>
                </th>
                <th>
                    <?= $d["time"] ?>
                </th>
                <th>
                    <a href="update_page.php?time=<?= $d["time"] ?>&worker_id=<?= $_GET['id'] ?>&day_id=<?= $d['day_id'] ?>&id=<?= $d["id"] ?>">⇧</a>
                </th>
                <th>
                    <a href="delete.php?id=<?= $_GET["id"] ?>&data_id=<?= $d["id"] ?>">❌</a>
                </th>
            </tr>
        <?php } ?>
    </table>
    <br>
    <form action="create.php?id=<?= $_GET['id'] ?>" style=" display:flex; justify-content: center;" method="post">
        <div class="update_form" style="background-color: #009879;">
            <label>
                <label>День недели</label>
                <br>
                <select name="day" style="margin-right: 15px;padding: 9px;">
                    <?php
                    $query = "SELECT schedule FROM work_schedule;";
                    $graphic = getData(pdo: $pdo, query: $query);
                    foreach ($week_day as $day) {
                    ?>
                        <option>
                            <?= $day["day"] ?>
                        </option>
                    <?php } ?>
                </select>

            </label>
            <br>
            <label>
                <label>Время</label>
                <br>

                <select name="time" style="padding: 9px;">

                    <?php
                    foreach ($graphic as $hours) { ?>
                        <option>
                            <?= $hours['schedule'] ?>
                        </option>
                    <?php
                    } ?>
                </select>

                <input type="submit" value="Добавить новый рабочий день.">
            </label>
        </div>
    </form>

</body>

</html>