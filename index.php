<?php
require_once dirname(__FILE__) . '/config.php';
require_once PHP . 'connection_db.php';
require_once PHP . 'functions.php';
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>телефонный список</title>
    <link rel="stylesheet" href="/css/reset.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
    <div class="shell">
        <form class="window_add_contact">
            <h2>
                Добавить контакт
            </h2>
            <h3 class="js_objee"></h3>
            <label for="name">
                <div>
                    <input id="name" type="text" name="name" <?= isset($phone->name)  ? "value='{$phone->name}'" : "" ?> placeholder="Имя">
                    <h4 class="js_name"></h4>
                </div>
            </label>
            <label for="phone">
                <div>
                    <input type="text" name="phone" <?= isset($phone->phone)  ? "value='{$phone->phone}'" : "" ?> placeholder="Телефон">
                    <h4 class="js_phone"></h4>
                </div>
            </label>
            <div class="css_block_btn">
                <button type="button" class="js_sending_data <?= (isset($phone)) ? "js_update" : "" ?>" data-path="add_phone" <?= isset($phone->phone)  ? "data-old_phone='{$phone->phone}'" : "" ?>>
                    Добавить
                </button>
            </div>
        </form>
        <div class="phone_list">
            <h2>
                Список контактов
            </h2>
            <main>
                <?php
                $array_phones = select_data($dbc, 'telephones_list');
                if (empty($array_phones)) {
                ?>
                    <section>
                        <P>контактов в справочнике нет</P>
                    </section>
                    <?php } else {
                    foreach ($array_phones as $data_phone) {
                    ?>
                        <section class="js_edit_data js_open_modal_window" data-path="modal_add_phone">
                            <p>
                                <?= $data_phone->name ?> <span class="js_remove_phone remove" data-path="remove_phone"></span>
                            </p>
                            <p class="phone">
                                <?= $data_phone->phone ?>
                            </p>
                        </section>
                        <!-- <tr>
                        <td class="css_data_table js_edit_data js_open_modal_window" data-path="modal_add_phone">
                            
                        </td>
                        <td class="css_data_table js_edit_data js_open_modal_window js_phone" data-path="modal_add_phone">
                            
                        </td>
                        <td>
                            
                        </td>
                    </tr> -->
                <?php
                    }
                }
                ?>
            </main>
        </div>
    </div>







    <?php

    //   echo __FILE__ . ' ' . __LINE__;
    //   echo '<pre>';
    //   print_r($array_phones);
    //   echo '</pre>';

    ?>
    <div class="modal_konteyner js_modal_konteyner">

    </div>
    <script src="/js/script.js"></script>
</body>

</html>