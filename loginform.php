<?php
require_once ('dbConnect.php');
if ($_POST) {
    $login = trim(htmlspecialchars(stripslashes($_POST['login'])));
    $password = password_hash(trim(htmlspecialchars(stripslashes($_POST['password']))), PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("SELECT `login`, `password` FROM user WHERE `login` = ?");
    $stmt->execute([$login]);
    $check = $stmt->fetch();
    if (isset($_POST['log_in'])) {
        if ($check && password_verify($_POST['password'], $check['password'])) {
            setcookie("logged_in", $login, $cookie_expiration_time);
            header("Location: index.php");
        } else {
            echo 'Введены неверные данные. Попробуйте еще раз.';
        }
    } elseif (isset($_POST['register'])) {
        if ($check !== false) {
            echo 'Такой пользователь уже есть!';
        } else {
            $add = $pdo->prepare("INSERT INTO user (login, password) VALUES (?, ?)");
            $add->execute([$login, $password]);
            setcookie("logged_in", $login, $cookie_expiration_time);
            header("Location: index.php");
        }
    }
}
?>