/**
 * Отображение и скрытие индикатора загрузки во время Ajax-запроса.
 * В момент запуска и окончания очереди Ajax-запросов, соответственно, показывает и скрывает указанный индикатор загрузки.
 *
 * @param selector Селектор объекта страницы, который выступает в роли индикатора загрузки
 */
function initAjaxListener(selector) {
    $(document).ajaxError(function(e, xhr, settings, exception) {
        if (xhr.status == 403) { // При ответе Forbidden
            // Выводим инструкцию по исправлению ошибки
            alert("Истекла сессия авторизации!\nОткройте сайт в новой вкладке и введите в форму авторизации свои учетные данные.\nПосле этого вернитесь к текущей вкладке и повторно попробуйте произвести последнее действие.");
        } else if (xhr.status != 200 && xhr.statusText != "abort" && xhr.responseText) { // При другой ошибке
            // Выводим текст ошибки
            var msg = xhr.responseText.match(/<body>(.|\n)+<\/body>/i);
            alert("Во время выполнения запроса произошла ошибка (" + xhr.statusText + "):\n" + (msg ? msg : xhr.responseText));
        }

        $(selector).fadeOut("slow");
    }).ajaxSend(function(e, xhr, settings) {
        if (prevXHR && canBrakeAjax) {
            prevXHR.abort();
        }

        prevXHR = xhr;

        // При запуске Ajax-запроса выводим индикатор загрузки
        fadeInBsElem(selector);
    }).ajaxComplete(function(e, xhr, settings) {
        prevXHR = null;

        // При завершении Ajax-запроса скрываем индикатор загрузки
        $(selector).fadeOut("slow");
    });
}