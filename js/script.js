// Открываем модальное окно
function open_modal_window(oe) {
    if (oe.target.classList.contains('js_remove_phone')) return;
    if (!oe.target.closest('.js_open_modal_window')) return;
    var button = oe.target.closest('.js_open_modal_window');
    var modal_konteyner = document.querySelector('.js_modal_konteyner');

    // предотвращаем повторное открытие формы
    if (modal_konteyner.children.length) return alert('форма уже открыта');

    var ajax = new XMLHttpRequest;
    var path = '/modal_window/' +  button.dataset.path + '.php'

    // Если изменяем номер
    if (button.classList.contains('js_edit_data')) {
        button.classList.add('js_edit_active');
        var phone = button.querySelector('.phone').innerText;
        path += '?phone=' + phone;
    }
    ajax.open('GET', path, false);
    ajax.send(null);
    if (ajax.status == 200) {
        if (ajax.responseText[0] == 2) {
            alert('редактируемый номер в базе отсуствует');
        } else {
            modal_konteyner.innerHTML = ajax.responseText;
            modal_konteyner.style.display = "flex";
            document.body.style.overflow = "hidden";
        }
        
    } else {
        alert("Ошибка на сервере, попробуйте позже")
    }
}

// Отправляем новые или редактируемые данные на сервер
function sending_data(oe) {
    if (!oe.target.closest('.js_sending_data')) return;
    var button = oe.target.closest('.js_sending_data');
    var form = button.form;
    // очищаем инфо заголовки формы, вдруг они заполнены
    var h4 = form.querySelectorAll('h4');
    for (var i = 0; i < h4.length; i++){
        h4[i].innerHTML = "";
    }

    var ajax = new XMLHttpRequest;
    var path = '/ajax_php/' + button.dataset.path + '.php'
    ajax.open('POST', path, true);
    // обработка результата ответа сервера
    ajax.onreadystatechange = function () {
        working_add_send_data(ajax, form); }

    // Формируем пост запрос
    var fd = new FormData(button.form)
    // Если редактировали до добавляем старый телефон, что бы можно было изменить на сервере
    if (button.classList.contains('js_update')) {
        fd.append('update', button.dataset.old_phone);
    }
    ajax.send(fd);
    
}

// Обработка ответа сервера по добавлению данных
function working_add_send_data(ajax, form) {
    if (ajax.readyState == 4 && ajax.status == 200) {
        var string_return_info = ajax.responseText;
        // Неправильная передача
        if (string_return_info[0] == 1) {
            form.querySelector('.js_objee').innerHTML = 'неправильная передача данных.';
        }
        // Незаполненные инпуты
        else if (string_return_info[0] == 2) {
            var array_empty_input = string_return_info.split(':')[1].split(' ');
            for (var i = 0; i < array_empty_input.length; i++) {
                form.querySelector('.js_' + array_empty_input[i]).innerText = "заполните данные";
            }
        }
        // Невалидный номер
        else if (string_return_info[0] == 3) {
            form.querySelector('.js_phone').innerText = "Номер должен состоять из цифр";
        }
        // Незаполненые инпуты и невалидный номер
        else if (string_return_info[0] == 4) {
            var array_empty_input = string_return_info.split(':')[1].split(' ');
            for (var i = 0; i < array_empty_input.length; i++) {
                form.querySelector('.js_' + array_empty_input[i]).innerText = "заполните данные";
            }
            form.querySelector('.js_phone').innerText = "Номер должен состоять из цифр";
        }
        // ошибка добавления
        else if (string_return_info[0] == 5) {
            form.querySelector('.js_objee').innerText = "Ошибка добавления! Попробуйте ещё раз или обратитесь к разработчику.";
        }
        // если номер уже существует
        else if (string_return_info[0] == 6) {
            form.querySelector('.js_objee').innerText = "Номер уже существует";
        }
        // успешное добавление
        else if (string_return_info[0] == 7) {
            var new_data = string_return_info.split(':')[1];            
            var array_new_data = new_data.split('&');
            // cloze_modal(null, true);
            var sectionEl = document.createElement('section');
            sectionEl.innerHTML = `
            <p>
                ${array_new_data[0]} 
                <span class="js_remove_phone remove" data-path="remove_phone"></span>
            </p>
            <p class="phone">
                ${array_new_data[1]}
            </p>
            `;
            sectionEl.classList.add('js_edit_data', 'js_open_modal_window');
            sectionEl.dataset['path'] = 'modal_add_phone';
            var mainEl = document.querySelector('main');
            // Если сообщение что нет записей
            if (mainEl.children.length == 1 && mainEl.children[0].children.length == 1) {
                mainEl.children[0].remove();
            }
            mainEl.appendChild(sectionEl);
            
            alert('данные добавлены ' + array_new_data[0] + ' ' + array_new_data[1]);
            document.querySelector('.window_add_contact').reset();
        }
        // успешное обновление
        else if (string_return_info[0] == 8) {
            var new_data = string_return_info.split(':')[1];
            var array_new_data = new_data.split('&');
            var array_td = document.querySelector('.js_edit_active').querySelectorAll('p');
            array_td[0].innerHTML = array_new_data[0] + '<span class="js_remove_phone remove" data-path="remove_phone"></span>';
            array_td[1].innerText = array_new_data[1];
            cloze_modal(null, true);
            alert('данные изменины на: ' + array_new_data[0] + ' ' + array_new_data[1]);
         }
    }
}

// закрытие модального окна
function cloze_modal(oe, flag) {    
    if ( (oe && !oe.target.closest('.js_cloze_modal')) && !flag) return;
    var modal_konteyner = document.querySelector('.js_modal_konteyner');
    modal_konteyner.innerHTML = "";
    modal_konteyner.style.display = "";
    document.body.style.overflow = "";
    // Если окно было открыто для редактирования то удаляем активный класс строки
    var active_row = document.querySelector('.js_edit_active');
    if (active_row) active_row.classList.remove('js_edit_active');
}

// Удаляем номер телефона
function remove_phone(oe) {
    if (!oe.target.closest('.js_remove_phone')) return;
    oe.stopPropagation();
    if (!confirm('Удалить номер из базы?')) return;
    var button = oe.target.closest('.js_remove_phone');
    var section = oe.target.closest('section');
    var phone = section.querySelector('.phone').innerText;
    var ajax = new XMLHttpRequest();
    var path = '/ajax_php/' + button.dataset.path + '.php?phone=' + phone;
    ajax.open('GET', path, false);
    ajax.send(null);
    if(ajax.responseText[0] == 3){
        alert('Указанного номера не существует')
    }
    else if(ajax.responseText[0] == 2){
        section.remove();
        var mainEl = document.querySelector('main');
        if (mainEl.querySelectorAll('section').length == 0) {
            mainEl.innerHTML = `<section>
                        <P>контактов в справочнике больше нет</P>
                    </section>`;
        }
    }
    
}


document.body.addEventListener('click', function (oe) {
    open_modal_window(oe);
    sending_data(oe);
    cloze_modal(oe);
    remove_phone(oe);
});
