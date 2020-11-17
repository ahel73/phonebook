<?php
/*
Возврат индексов
1 - неправильная передача данных
2 - номер удалён
3 - ошибка удаления

*/
require_once '../config.php';
require_once PHP . 'connection_db.php';
require_once PHP . 'functions.php';

if(empty($_GET['phone'])) exit(1);
$phone = filtr_vhodnoy_stroki($_GET['phone'], 1);
if(delete_data($dbc, 'telephones_list', 'phone', $phone)){
    exit('2');
}else{
    exit('3');
}