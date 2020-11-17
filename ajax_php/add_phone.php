<?php
/*
Добавляем новый телефон и данные по нему, либо редактируем телефон и его данные

Возврат индексов
1 - неправильная передача данных
2 - не незаполненность
3 - неправильно передан номер
4 - не заполненость и неправильный номер
5 - ошибка при добавлении
6 - телефон уже существует
7 - успешное добавление
8 - успешное обновление

*/


require_once '../config.php';
require_once PHP . 'connection_db.php';
require_once PHP . 'functions.php';
// echo __FILE__ . ' ' . __LINE__;
// echo '<pre>';
// print_r($_POST);
// echo '</pre>';

// 1. проверяем на заполненость и валидность

// Если передача не постом
if(empty($_POST)){
    exit(1);
} 

// Цикл на заполняемость и валидность
$string_empty_input = "";
foreach($_POST as $key => $value){
    // Проверка на заполняемость
    if(empty($value)){
        $string_empty_input .= $key . ' ';
        continue;
    }

    // Проверка на валидность
    // Проверяем имя
    if($key == 'name'){
        $name =  filtr_vhodnoy_stroki($value, 4, false);
    }
    // Проверяем телефон
    else if($key == "phone"){
        $phone = '';
        $error_phone = null;
        for($i = 0; $i < strlen($value); $i++){
            if($value[$i] == '0'){
                $phone .= $value[$i];
            }else if($value[$i] != '0' && (int) $value[$i]){
                $phone .= $value[$i];
            }
        }
        
        if(empty($phone)) $error_phone = true; 
    }
}

// Если инпуты не заполнены
if(!empty($string_empty_input) && !isset($error_phone)){
    exit('2:' . trim($string_empty_input));
}
// Если инпуты заполнены но номер не валидный
else if(empty($string_empty_input) && isset($error_phone)){
    exit('3');
}
// Если и инпут не заполнен и номер не валидный
else if (!empty($string_empty_input) && isset($error_phone)) {
    exit('4:'. trim($string_empty_input));
}

// если редактирование данных по номеру то валидируем старый номер
if (isset($_POST['update'])){
    $old_phone =
    filtr_vhodnoy_stroki($_POST['update'], 2);
}


// 2. проверяем на существование телефона в базе если добавляется новый или если  редактируемый не совпадает со старым (может быть дублирование в базе)
if(!isset($old_phone) || $old_phone != $phone){
    select_data($dbc, 'telephones_list', "phone = '{$phone}'") && exit('6');
}

// 3. если обнавление то обнавляем 
if(isset($old_phone)){
    
     if(update_data($dbc, 'telephones_list', "name='{$name}', phone='{$phone}'", "phone='{$old_phone}'")){
        if(select_data($dbc, 'telephones_list', "phone = '{$phone}'")){
            exit("8:{$name}&{$phone}");
        }
     }else{
        exit('5');
     }
    
    
}



// 4. добавляем в базу
$new_id = insert_data($dbc, 'telephones_list', 'name, phone', "'{$name}','{$phone}'");

// 4. возвращаем добавленный телефон
if(!empty($new_id)){
    exit("7:{$name}&{$phone}");
}else{
    exit('5');
}