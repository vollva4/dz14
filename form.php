<?php
require_once ('dbConnect.php');
if (isset($_COOKIE["logged_in"])) {
    $login = $_COOKIE["logged_in"];
    $addButton = 'Добавить'; 
    $select = "SELECT t.id as task_id, t.description as description, u.id as author_id, u.login as author_name, au.id as assigned_user_id, au.login as assigned_user_name, t.is_done as is_done, t.date_added as date_added FROM task t WHERE u.login = $login INNER JOIN user u ON u.id=t.user_id INNER JOIN user au ON t.assigned_user_id=au.id";
    if($_GET) {
        $id = $_GET['id'];
        if ($_GET['action'] === 'delete') {
            $delPrep = $pdo->prepare("DELETE FROM task WHERE id = ?");
            $delPrep->execute([$id]);
            $description = $delPrep->fetch()['description'];
            header ('Location: index.php');
            exit();
        }
        if ($_GET['action'] === 'done') {
            $donePrep = $pdo->prepare("UPDATE task SET is_done = TRUE WHERE id = ? LIMIT 1");
            $donePrep->execute([$id]);
            $description = $donePrep->fetch()['description'];
            header ('Location: index.php');
            exit();
        }
        if ($_GET['action'] === 'edit') {
            $idPrep = $pdo->prepare("SELECT * FROM task WHERE id = ?");
            $idPrep->execute([$id]);
            $description = $idPrep->fetch()['description'];
            $addButton = 'Сохранить';
        }
    }
    //Добавление и редактирование задач
    if (isset($_POST['add'])) {
        $desc = $_POST['description'];
        $id = $_POST['id'];
        if ($id) {
            $editPrep = $pdo->prepare("UPDATE task SET description = ? WHERE id = ? LIMIT 1");
            $editPrep->execute([$desc, $id]);
        } else {
            $currentUser = $pdo->prepare("SELECT id, login FROM user WHERE login = ?");
            $currentUser->execute([$login]);
            $user = $currentUser->fetch();
            $addPrep = $pdo->prepare("INSERT INTO task (description, is_done, date_added, user_id, assigned_user_id) VALUES (?, ?, CURRENT_TIMESTAMP, ?, ?)");
            $addPrep->execute([$desc, false, $user['id'], $user['id']]);
        }
    }
    //Упорядочение задач
    $allowedSort = ['description', 'date_added', 'is_done'];
    if (isset($_POST['sort'])) {
        if(array_search($_POST['sortBy'], $allowedSort) !== false) {
            $sortBy = addslashes($_POST['sortBy']);
            $select .= " ORDER BY $sortBy";
        }
    }
    //Список пользователей для перекладывания ответственности
    $users = [];
    foreach ($pdo->query("SELECT login FROM user") as $user){
        $users[] = $user['login'];
    }
    //Перекладывание ответственности
    if (isset($_POST['assign'])) {
        if (array_search($_POST['assign_to'], $users) !== false) {
            $assign_to_name = $pdo->quote($_POST['assign_to']);
            $taskId = $_POST['id'];
            $assign_to_id = $pdo->query("SELECT id, login FROM user WHERE login = $assign_to_name")->fetch()['id'];
            $assignPrep = $pdo->prepare("UPDATE task SET assigned_user_id = ? WHERE id = ? LIMIT 1");
            $assignPrep->execute([$assign_to_id, $taskId]);
        }
    }
}  else {
    echo '<a href="register.php">Войдите или зарегистрируйтесь</a>';
}
?>