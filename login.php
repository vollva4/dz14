<?php
error_reporting(E_ALL);
include 'dbConnect.php';
$showall = $pdo->query('SHOW TABLES from avolvach');
foreach ($showall as $key => $value) {
    echo '<table>';
    print_r ($value);
}
?>

<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добро пожаловать!</title>
</head>
<body>
<p>Войдите или зарегистрируйтесь:</p>
<form method="post" action="loginform.php">
    <input name="login" placeholder="Логин">
    <input type="password" name="password" placeholder="Пароль">
    <input type="submit" name="log_in" value="Вход">
    <input type="submit" name="register" value="Регистрация">
</form>
</body>
</html>