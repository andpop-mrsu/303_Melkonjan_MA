<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles.css">
    <title>Информация о мастерах</title>
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
    <table border="1" cellpadding="10" class="table">
        <thead>
            <th>ФИО</th>
            <th>Специализация</th>
            <th>Часы работы</th>
            <th></th>
            <th>Update</th>
            <th>Delete</th>
        </thead>
        <?php
        require_once "./config/connect.php";

        $query = "SELECT first_n,second_n,middle_n,specialization,workers.id FROM workers 
                  INNER JOIN specializations s ON s.id = workers.specialization_id WHERE status = 'working'
                  ORDER BY second_n";

        $data = getData(pdo: $pdo, query: $query);

        ?>
        <?php foreach ($data as $d) { ?>
            <tr>
                <th>
                    <?= $d['second_n'], ' ', $d['first_n'], ' ', $d['middle_n'] ?>
                </th>
                <th>
                    <?= $d['specialization'] ?>
                </th>
                <th>
                    <a href="time_table.php?id=<?= $d['id'] ?>">График</a>
                </th>
                <th>
                    <a href="completed_work.php?id=<?= $d['id'] ?>">Выполненые работы</a>
                </th>
                <th>
                    <a href="update_page.php?worker_id=<?= $d['id'] ?>&specialization=<?= $d['specialization'] ?>">⇧</a>
                </th>
                <th>
                    <a href="delete.php?id=<?= $d['id'] ?>">❌</a>
                </th>
            </tr>
        <?php } ?>
    </table>
    <div class="add_master">
        <h2 style="text-align:center;font-family: sans-serif; color: #ffffff;">Добавить мастера.</h2>
        <form action="create.php" style=" display:flex; justify-content: center;" method="post">

            <input type="text" placeholder="Фамилия" style="margin-right:15px;" name="second_n">
            <input type="text" placeholder="Имя" style="margin-right:15px;" name="first_n">
            <input type="text" placeholder="Отчество" style="margin-right:15px;" name="middle_n">

            <label>
                <?php
                $query = "SELECT * FROM specializations;";
                $data = getData(pdo: $pdo, query: $query);
                ?>
                <label style="color: white; font-family: sans-serif">Специализация</label><br><br>
                <select style="width: 150px;" name="specialization">
                    <?php foreach ($data as $d) { ?>
                        <option value=<?= $d['id'] ?>>
                            <?= $d['specialization'] ?>
                        </option>
                    <?php } ?>
                </select>
            </label>
            <input type="submit" value="Добавить" style="margin-left:15px;">
        </form>
    </div>
</body>

</html>