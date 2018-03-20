<?php
require_once ('dbConnect.php');
try {
    $sql = "DROP TABLE IF EXISTS `task`;
CREATE TABLE `task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `is_done` tinyint(4) NOT NULL DEFAULT '0',
  `date_added` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
    $pdo->exec($sql);
    echo "Таблица создана успешно.<br>";
}
catch (PDOException $e) {
    echo 'Что-то тут не так: ',  $e->getMessage();
}

?>