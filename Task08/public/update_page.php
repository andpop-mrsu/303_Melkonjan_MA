<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./styles.css">
    <title>Обновление информации.</title>
</head>

<body>
    <?php
    require_once "./config/connect.php";
    $query = "SELECT first_n,second_n,middle_n,specialization_id FROM workers WHERE id == {$_GET['worker_id']};";
    $data_ = getData(pdo: $pdo, query: $query);

    if (explode('?', explode('/', $_SERVER["HTTP_REFERER"])[5])[0] == 'index.php') {
    ?>
        <h2>
            Обновление информации : "<?= $data_[0]["second_n"], ' ', $data_[0]["first_n"], ' ', $data_[0]["middle_n"] ?>".
        </h2>

        <form method="post" style="display:flex; justify-content: center;" action="update.php?worker_id=<?= $_GET["worker_id"] ?>">
            <div class="update_form">
                <label>Фамилия</label>
                <br>
                <input name="$second_n" type="text" value="<?= $data_[0]["second_n"] ?>">
                <br><br>
                <label>Имя</label>
                <br>
                <input name="first_n" type="text" value="<?= $data_[0]["first_n"] ?>">
                <br><br>
                <label>Отчество</label><br>
                <input name="middle_n" type="text" value="<?= $data_[0]["middle_n"] ?>">
                <br><br>
                <label>Сециализация</label><br>
                <?php
                $query = "SELECT * FROM specializations;";
                $data = getData(pdo: $pdo, query: $query);
                ?>

                <select name="specialization">
                    <?php
                    foreach ($data as $d) {
                        if ($d['specialization'] === $_GET['specialization']) { ?>
                            <option selected="selected" value='<?= $_GET['specialization'] ?>'>
                                <?= $_GET['specialization'] ?>
                            </option>
                        <?php
                            continue;
                        } ?>
                        <option value=<?= $d["specialization"] ?>>
                            <?= $d["specialization"] ?>
                        </option>
                    <?php } ?>
                    <br>
                    <br>
                </select>
                <input type="submit" value="Изменить.">
            </div>
        </form>
    <?php } elseif (explode('?', explode('/', $_SERVER["HTTP_REFERER"])[5])[0] === 'time_table.php') {
        $query = "SELECT * FROM day_id;";
        $week_day = getData(pdo: $pdo, query: $query);
        $query = "SELECT schedule FROM work_schedule;";
        $graphic = getData(pdo: $pdo, query: $query);
    ?>
        <h2>
            Обновление графика мастера -
            "<?= $data_[0]["second_n"], ' ', $data_[0]["first_n"], ' ', $data_[0]["middle_n"] ?>".
        </h2>
        <form method="post" style=" display:flex; justify-content: center;" action="update.php?worker_id=<?= $_GET['worker_id'] ?>&id=<?= $_GET['id'] ?>&day_id=<?= $_GET['day_id'] ?>">
            <div class="update_form">
                <label>День недели</label>
                <br>

                <select name="day">
                    <?php foreach ($week_day as $day) {
                        if ($_GET["day_id"] == $day["id"]) {
                    ?>
                            <option selected="selected">
                                <?= $day["day"] ?>
                            </option>
                        <?php
                            continue;
                        } ?>
                        <option>
                            <?= $day["day"] ?>
                        </option>
                    <?php } ?>
                </select>

                <br><br>
                <label>Время</label>
                <br>

                <select name="time">
                    <?php
                    foreach ($graphic as $hours) {
                        if ($hours['schedule'] === $_GET["time"]) { ?>
                            <option selected="selected" value=<?= $_GET["time"] ?>>
                                <?= $_GET["time"] ?>
                            </option>
                        <?php
                            continue;
                        } ?>
                        <option>
                            <?= $hours['schedule'] ?>
                        </option>
                    <?php
                    } ?>
                </select>

                <input type="submit" value="Изменить.">
            </div>
        </form>
    <?php } ?>
</body>

</html>