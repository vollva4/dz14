<?php
error_reporting(E_ALL);
print_r($_POST);
print_r($_GET);
require_once ('dbConnect.php');
require_once ('form.php');
$selectOne = "SELECT t.id as task_id, t.description as description, u.id as author_id, u.login as author_name, au.id as assigned_user_id, au.login as assigned_user_name, t.is_done as is_done, t.date_added as date_added FROM task t  INNER JOIN user u ON u.id=t.user_id INNER JOIN user au ON t.assigned_user_id=au.id WHERE u.login = '$login' ;";
$selectTwo = "SELECT t.id as task_id, t.description as description, u.id as author_id, u.login as author_name, au.id as assigned_user_id, au.login as assigned_user_name, t.is_done as is_done, t.date_added as date_added FROM task t  INNER JOIN user u ON u.id=t.user_id INNER JOIN user au ON t.assigned_user_id=au.id WHERE au.login = '$login' AND u.login != '$login' ;";
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>ДЗ к занятию 14 PHP</title>
    <style>
table {
    margin-top: 20px;
            border-collapse: collapse;
        }
        td, th {
    border: 1px solid black;
            padding: 5px 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<h1>Привет, <?=$login?>! Вот ваш список задач:</h1>

    <form method="post" action="index.php">
        <input type="hidden" name="id" value="<?= $_GET ? $_GET['id'] : "" ?>">
        <input placeholder="Описание задачи" name="description" value="<?= $_GET ? $description : "" ?>">
        <input type="submit" value="<?= $addButton ?>" name="add">
        Сортировать по:
        <select name="sortBy">
            <option value="description">Описанию</option>
            <option value="date_added">Дате добавления</option>
            <option value="is_done">Статусу</option>
        </select>
        <input type="submit" value="Отсортировать" name="sort">
    </form>
    <table>
        <tr>
            <th>Описание задачи</th>
            <th>Дата добавления</th>
            <th>Статус</th>
            <th>Действия</th>
            <th>Ответственный</th>
            <th>Автор</th>
            <th>Закрепить задачу за пользователем</th>
        </tr>
        <?php
        //Наполнение таблицы деталями каждой задачи
        $stmt = $pdo->prepare($selectOne);
        $stmt->execute();
        $list = $stmt->fetchAll();
        foreach ($list as $row) {
 //           if ($login === $row['author_name']) { //Только задачи за авторством текущего пользователя
                echo '<tr>
                          <td>' . $row['description'] . '</td>
                          <td>' . $row['date_added'] . '</td>
                          <td>';
                            if (intval($row['is_done']) === 1)
                            {
                                echo '<span style="color: darkgreen">Выполнено</span>';
                            } elseif (intval($row['is_done']) === 0)
                            {
                                echo '<span style="color: darkorange">В процессе</span>';
                            } else
                                echo '<span style="color: red">В неопределенном состоянии</span>
                          </td>';
                          echo '<td><a href="index.php?id=' . $row['task_id'] . '&action=edit">Редактировать</a> ';
                            if ($login === $row['assigned_user_name'])
                            {
                                echo '<a href="index.php?id=' . $row['task_id'] . '&action=done"> Выполнить</a> ';
                            }
                            echo '<a href="index.php?id=' . $row['task_id'] . '&action=delete">Удалить</a>
                          </td>
                          <td>' . $row['assigned_user_name'] . '</td>
                          <td>' . $row['author_name'] . '</td>
                          <td>';
                ?>
                <form method="post" action="index.php">
                    <input type="hidden" name="id" value="<?= $row['task_id'] ?>">
                    <select name="assign_to">
                        <?php
                        foreach ($users as $user) {
                            if ($login !== $user) {
                                echo '<option>' . $user . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <input type="submit" value="Переложить ответственность" name="assign">
                </form>
                </td></tr>

                <?php
       //     }
        }
        ?>
    </table>
    <h2>А вот что требуют от вас другие:</h2>
    <table>
    <tr>
        <th>Описание задачи</th>
        <th>Дата добавления</th>
        <th>Статус</th>
        <th>Действия</th>
        <th>Ответственный</th>
        <th>Автор</th>
    </tr>
    <?php
    $stmt = $pdo->prepare($selectTwo);
    $stmt->execute();
    $list = $stmt->fetchAll();
    foreach ($list as $row) {
        // Только задачи для текущего пользователя от других пользователей
 //       if ($login === $row['assigned_user_name'] && $login !== $row['author_name']) {
            echo '<tr>
                    <td>' . $row['description'] . '</td>
                    <td>' . $row['date_added'] . '</td>
                    <td>';
            if (intval($row['is_done']) === 1) {
                echo '<span style="color: darkgreen">Выполнено</span>';
            } elseif (intval ($row['is_done']) === 0) {
                echo '<span style="color: darkorange">В процессе</span>';
            } else
                echo '<span style="color: red">В неопределенном состоянии</span>';
            echo '</td>
                  <td><a href="index.php?id=' . $row['task_id'] . '&action=edit">Редактировать</a> 
                      <a href="index.php?id=' . $row['task_id'] . '&action=done"> Выполнить</a>
                      <a href="index.php?id=' . $row['task_id'] . '&action=delete">Удалить</a>
                  </td>
                  <td>' . $row['assigned_user_name'] . '</td>
                  <td>' . $row['author_name'] . '</td>';
      //  }
    }
    ?>

    </table>

<br>
<a href="logout.php">Выйти</a>
</body>
</html>