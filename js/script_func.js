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