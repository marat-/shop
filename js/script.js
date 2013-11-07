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
            $(".captcha-container").css("visibility","visible").hide().fadeIn().removeClass("hidden");
        } else {
            alert(data.html_msg);
        }
    }

    // Если имеется сообщение
    if (data.msg) {
        showMessage(data.msg)
    }
}
