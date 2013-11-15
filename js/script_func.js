/**
 * Смена текущей страницы.
 * Если адрес пустой, произойдет перезагрузка текущей страницы.
 *
 * @param url Адрес новой страницы
 */
function reloadPage(url) {
    if (!url) {
        url = window.location.href;
    }

    window.location.href = url;
}

/**
 * Отображение сообщения
 *
 * @param messageText текст сообщения
 */
function showMessage(messageText) {
    var msg_class = "";
    if (messageText.indexOf("error|") > -1) {
        msg_class = "error";
        messageText = messageText.replace("error|", "");
    } else if(messageText.indexOf("warning|") > -1) {
        msg_class = "warning";
        messageText = messageText.replace("warning|", "");
    } else if(messageText.indexOf("success|") > -1) {
        msg_class = "success";
        messageText = messageText.replace("success|", "");
    } else {
        msg_class = "info";
        messageText = messageText.replace("info|", "");
    }
    $(".site-message").remove();
    $(".message-container").prepend("<span class='text-" + msg_class + " hidden site-message'>" + messageText + "</span>");
    $(".site-message").css('visibility','visible').hide().fadeIn().removeClass('hidden');
}

/**
 * Обработчик ответа на аякс формы
 *
 * @param data ответ сервера
 * @param form элемент формы
 */
function formSubmitHandler(data, form) {
    // Если ответ положительный
    if (data === true) {
        // переходим на нужную страницу
        if ($.trim(form.attr("rel"))) {
            reloadPage(form.attr("rel"));
        }
    }
    // иначе выдаем сообщение об ошибке
    // командные сообщения
    if (data.cmd_msg) {
        if (data.cmd_msg.indexOf("reload|") > -1) {
            if(data.cmd_msg.indexOf("link|") > -1) {
                reloadPage(passport_url + "login/" + data.cmd_msg.replace("reload|", "").replace("link|", ""));
            } else {
                alert(data.cmd_msg.replace("reload|", ""));
                reloadPage(form.attr("rel"));
            }
        }
    }

    // Сообщения, содержание html
    if (data.html_msg) {
        if(data.html_msg.indexOf("captcha") > -1) {
            $(".captcha-container").empty();
            $(".captcha-container").append(data.html_msg);
            fadeInBsElem(".captcha-container");
        } else {
            alert(data.html_msg);
        }
    }

    // Если имеется сообщение
    if (data.msg) {
        showMessage(data.msg)
    }
}

/*
 * Отображение скрытого бутстрап поля
 */
function fadeInBsElem(elem) {
    $(elem).css("visibility","visible").fadeIn().removeClass("hidden");
}

function removeCLEditorInstances() {
    for(var i in cleditor_instances) {
        var editor = cleditor_instances[i][0];
        // Remove the editor
        editor.$area.insertBefore(editor.$main); // Move the textarea out of the main div
        editor.$area.removeData("cleditor"); // Remove the cleditor pointer from the textarea
        editor.$main.remove(); // Remove the main div and all children from the DOM
    }
    cleditor_instances = [];
}
