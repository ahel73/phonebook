<?php
  //Соединение с базой данных
  
  if($_SERVER['HTTP_HOST'] == 'ts'){
    define('HOST', 'localhost');
    define('USER_NAME', 'ahel73');
    define('PASSWORD', '');
    define('DATA_BASE', 'db_telephones');
  }else{
    define('HOST', 'localhost');
    define('USER_NAME', 'u0455254_phone');
    define('PASSWORD', 'qwerty1122334455');
    define('DATA_BASE', 'u0455254_phone');
  }
  
  
  $dbc = mysqli_connect(HOST, USER_NAME, PASSWORD, DATA_BASE)or die('Ошибка подключения к MySQL серверу.');
  if(!empty($dbc)){
    mysqli_set_charset($dbc, 'utf8');
  }

  
