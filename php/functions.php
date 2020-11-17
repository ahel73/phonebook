<?php

// filtr_vhodnoy_stroki - проверяет данные полученные из форм  браузера на допустимость безопасности
function filtr_vhodnoy_stroki($string, $flag_type = 1, $flag_tag = true)
{

    /*  ПРОВЕРЯЕТ ВХОДНЫЕ СТРОКИ И ПРИ НЕОБХОДИМОСТИ ПРЕОБРАЗУЕТ ИХ В ЧИСЛА
   * - $string - входная строка
   * 
   * - $flag_type - определяет тип данных который необходимо вернуть из функции в случаи успешной обработки строки
   *   - 1 - строка
   *   - 2 - целое число
   *   - 3 - дробное число
   *   - 4 - строка с удалёнными начальными и конечными пробелами
   * 
   * - $flag_tag - указывает удалять или нет html теги в строке
   *   - true - не удалять
   *   - false - удалять
  */
    if ($flag_type == 2 || $flag_type == 3) {
        $string = trim($string);
        if ($flag_type == 2) return filter_var($string, FILTER_VALIDATE_INT);
        if ($flag_type == 3) return filter_var($string, FILTER_VALIDATE_FLOAT);
    } else {
        if ($flag_type == 4) {
            $string = trim($string);
        }
        if ($flag_tag === false) {
            // удаляем теги
            $string = filter_var($string, FILTER_SANITIZE_STRING);
        }

        $string = filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
        return preg_replace('/\s--\s/', '&#32;&#45;&#45;&#32;', $string);
    }
}

// select_data - запрашивает данные из базы формирует массив и возвращает его либо ложь
function select_data($id_dbc, $tables, $where = null, $data_list = '*', $string_sort = null, $sort = 'ASC', $polniy_zapros = null)
{

    if ($polniy_zapros === null) {
        $stroka_zaprosa = "SELECT " . $data_list . " FROM " . $tables;
        if (isset($where)) {
            $stroka_zaprosa .= ' WHERE ' . $where;
        }
        if ($string_sort !== null) {
            $stroka_zaprosa .=  " ORDER BY " . $string_sort . " " . $sort;
        }

    } else {
        $stroka_zaprosa = $polniy_zapros;
    }

    $return_array = [];
    
    // echo $stroka_zaprosa . '<br>';
    $obj_zaprosa = mysqli_query($id_dbc, $stroka_zaprosa);

    if ($obj_zaprosa->num_rows == 0) {
        return false;
    }
    while ($stroka_zaprosa = mysqli_fetch_object($obj_zaprosa)) {
        $return_array[] = $stroka_zaprosa;
    }
    return $return_array;
}


// insert_data - добавляет данные в таблицу. 
// принимает:
// $obj_dbs - объект подключения
// table_name - имя таблицы
// $stroka_stolbcov - строка столбцов
// $stroka_dannih - строка добавляемых данных
// $polniy_zapros - полный запрос
// Возвращает идентификатор добавления либо 0 в случае неудачи
function insert_data($obj_dbs, $table_name, $stroka_stolbcov, $stroka_dannih, $polniy_zapros = null)
{

    if (!isset($polniy_zapros)) {
        $zapros = "INSERT INTO " . $table_name . " (" . $stroka_stolbcov . ") VALUES (" . $stroka_dannih . ")";
    } else {
        $zapros = $polniy_zapros;
    }
    // echo $zapros . '<br>';
    if (!mysqli_query($obj_dbs, $zapros)) {
        return 0;
    }


    return mysqli_insert_id($obj_dbs);
}

// update_data - обнавляет данные в таблицы и возвращает количество изминённых строк
function update_data($obj_dbs, $table_name, $chto_menyem, $sravnenie)
{
    $update_string = "UPDATE {$table_name} SET {$chto_menyem} WHERE {$sravnenie}";

    // echo $update_string . '<br>';
    if (!mysqli_query($obj_dbs, $update_string)) {
        return 0;
    }

    return mysqli_affected_rows($obj_dbs);
}

// delete_data - удаляем данные из таблицы
function delete_data($obj_dbs, $table_name, $atr, $value){
$delete_string = "DELETE FROM {$table_name} WHERE {$atr} = {$value}";
    //   echo $delete_string . '<br>';
    if (mysqli_query($obj_dbs, $delete_string)) {
        return 1;
    }else{
        return 0;
    }
}
