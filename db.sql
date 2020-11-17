-- Создание базы
CREATE DATABASE IF NOT EXISTS db_telephones  DEFAULT CHARACTER SET = utf8 COLLATE=utf8_general_ci

-- Создаём таблицу 
CREATE TABLE telephones_list
(
    id_telefon INT(7) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name varchar(45) NULL,
    phone varchar(45) NULL
)