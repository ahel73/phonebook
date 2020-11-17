<?php
// форма по добавлению нового номера или редактирования старого
require_once '../config.php';
require_once PHP . 'connection_db.php';
require_once PHP . 'functions.php';

if (!empty($_GET['phone'])) {
    $phone = filtr_vhodnoy_stroki($_GET['phone'], 1);
    $array_query = select_data($dbc, 'telephones_list', "phone = '{$phone}'");
    if (is_array($array_query)) {
        $phone = $array_query[0];
    } else {
        exit('2');
    }
}
?>
<form>
    <h4 class="js_objee"></h4>
    <label for="name">
        <h3>
            Наименование:
        </h3>
        <div>
            <input id="name" type="text" name="name" <?= isset($phone->name)  ? "value='{$phone->name}'" : "" ?>>
            <h4 class="js_name"></h4>
        </div>
    </label>
    <label for="phone">
        <h3>
            Телефон:
        </h3>
        <div>
            <input type="text" name="phone" <?= isset($phone->phone)  ? "value='{$phone->phone}'" : "" ?>>
            <h4 class="js_phone"></h4>
        </div>
    </label>
    <div class="css_block_btn">
        <button type="button" class="js_sending_data <?= (isset($phone)) ? "js_update" : "" ?>" data-path="add_phone" <?= isset($phone->phone)  ? "data-old_phone='{$phone->phone}'" : "" ?>>
            Записать
        </button>
        <button type="button" class="cloze_modal js_cloze_modal" data-path="add_phone">
            Отмена
        </button>
    </div>
</form>