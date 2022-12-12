<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="./styles.css">
    <title>Запись на прием</title>
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
    $query = "SELECT * FROM services;";
    $data = getData(pdo: $pdo, query: $query);
    ?>

    <br>
    <br>
    <form action="#" style="display:flex; justify-content: center;">

        <div class="register_form_background">
            <div class="register_form">
                <label>Услуга</label>
                <br>
                <br>
                <select name="service">
                    <?php foreach ($data as $d) {
                        if ($_GET["service"] == $d['id']) { ?>
                            <option selected="selected" value="<?= $d["id"] ?>">
                                <?= $d['name_service'] ?>
                            </option>
                        <?php continue;
                        } ?>
                        <option value="<?= $d["id"] ?>">
                            <?= $d['name_service'] ?>
                        </option>
                    <?php } ?>
                </select>
                <?php if (empty($_GET['service'])) { ?>
                    <input value="Выбрать" type="submit">
                <?php } ?>
            </div>
            <?php if (!empty($_GET['service'])) {
                $query = "SELECT *
                      FROM workers
                      WHERE specialization_id = (
                      SELECT specialization_id
                      FROM service_specialization
                      WHERE service_id = {$_GET['service']}) AND status='working';";
                $worker_data = getData(pdo: $pdo, query: $query);
            ?>

                <form>
                    <div class="register_form">
                        <label>Мастер</label>

                        <select name="worker">
                            <?php foreach ($worker_data as $w_data) {
                                if ($_GET["worker"] == $w_data['id']) { ?>
                                    <option selected="selected" value="<?= $w_data['id'] ?>">
                                        <?= $w_data['second_n'], ' ', $w_data['first_n'], ' ', $w_data['middle_n'] ?>
                                    </option>
                                <?php continue;
                                } ?>
                                <option value="<?= $w_data['id'] ?>">
                                    <?= $w_data['second_n'], ' ', $w_data['first_n'], ' ', $w_data['middle_n'] ?>
                                </option>
                            <?php } ?>
                        </select>
                        <?php if (empty($_GET['worker'])) { ?>
                            <input value="Выбрать" type="submit">
                        <?php } ?>

                        <br>

                        <?php if (!empty($_GET['worker'])) { ?>
                            <p>
                                <label>Мастер рабоает в следующие дни : </label>
                            <div class="time_table">
                                <?php
                                $query = "SELECT day_id FROM time_table WHERE worker_id = '{$_GET['worker']}';";
                                foreach (getData($pdo, $query) as $day_id) { ?>
                                    <br>
                                    <?php
                                    $query = "SELECT day FROM day_id WHERE id = ($day_id[0]);";
                                    print_r(getData($pdo, $query)[0][0] . "\n\n"); ?>
                                    <br>
                                <?php
                                }
                                ?>
                                <br>
                            </div>
                            </p>
                            <input name="date" type="date" value=<?= $_GET['date'] ?>>

                            <input value="Выбрать" type="submit">


                        <?php } ?>

                    </div>
                </form>

            <?php }
            if (!empty($_GET['date'])) {
                $day_id = date('w', strtotime($_GET['date']));
                $query = "SELECT * FROM time_table WHERE worker_id = '{$_GET['worker']}' AND day_id = {$day_id};";
                $time_data = getData(pdo: $pdo, query: $query);
                $time_dil = explode('⠀-⠀', $time_data[0]['time']);
                $query = "SELECT duration FROM services WHERE id = {$_GET['service']};";
                $time_offset = getData(pdo: $pdo, query: $query)[0][0] + 5;

                $hours = array();
                $hours[0] = $time_dil[0];
                $current_hour = $time_dil[0];
                $time_dil[1] = date('H:i', strtotime("-" . $time_offset . " Minutes", strtotime($time_dil[1])));
                $i = 0;

                while ($current_hour < $time_dil[1]) {
                    $i++;
                    $current_hour = date('H:i', strtotime("+" . $time_offset . " Minutes", strtotime($hours[$i - 1])));
                    $hours[$i] = $current_hour;
                }
            ?>
                <div class="register_form">
                    <?php
                    if (empty($_GET['hour'])) {
                        if ($hours[0] != "") { ?>
                            <label>Время</label>
                            <select name="hour">
                                <?php foreach ($hours as $hour) { ?>
                                    <option>
                                        <?= $hour ?>
                                    </option>
                                <?php } ?>
                            </select>
                        <?php } else {
                            die("Мастер в этот день не работает.");
                        } ?>
                        <?php ?>
                        <input value="Выбрать" type="submit">
                    <?php } else { ?>
                        <label style="text-align:-moz-center">Время</label>
                        <br><br>
                        <div class="hidden_option">
                            <option selected disabled>
                                <?= $_GET['hour'] ?>
                            </option>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <?php if (!empty($_GET['hour'])) {
            ?>
                <form class="register_form" method="post" action="
              create.php?worker_id=<?= $_GET['worker'] ?>&service_id=<?= $_GET['service'] ?>&day=<?= $_GET['date'] . " " . $_GET['hour'] ?>">

                    <input type="submit" value="Записать">
                </form>
            <?php } ?>
        </div>
    </form>


</body>

</html>