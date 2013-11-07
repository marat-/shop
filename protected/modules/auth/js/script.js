$(function() {
    initAuthForm();
});

/**
 * Инициализация формы авторизации
 */
function initAuthForm(){
    var b = $.browser;
    var ver = b.version.split(".");

    var browser = "";
    jQuery.each(b, function(i, val) {
        browser += i + " = " + val + "; ";
    });

    browser += "screen = " + screen.width + " x " + screen.height + "; ";
    browser += "window = " + $(window).width() + " x " + $(window).height();

    $("input[name=browser]").val(browser);

    // Проверка среды выполнения
    if (!$.cookie("cookie_check")) {
        $("#cookie_error").css('visibility','visible').hide().fadeIn().removeClass('hidden');
    }

    if (!(b.webkit || b.mozilla) || (b.webkit && ver[0] < 10) || (b.mozilla && ver[0] < 5)) {
        $("#browser_error").css('visibility','visible').hide().fadeIn().removeClass('hidden');
    }

    $(".news-detail-link").unbind().bind("click", function() {
        var news_id = $(this).attr("news_id");
        $.ajax({
            url: passport_url + 'login/get_news_detail',
            type: 'POST',
            data: {
                "news_id" : news_id
            },
            success: function(data){
                $("#news-detail").remove();
                $("body").append(data);
                $('#news-detail').modal();
            },
            async: true,
            dataType: "html"
        });
    });

    $(".new-password").live("keyup", function () {
        var rating = getPasswordStrength($(".new-password").val(), $(".login").val());
        $(".password-strength td").removeClass();
        for(var i = 0; i < $(".password-strength td").length; i++){
            if(i <= rating.rate - 1) {
                $(".password-strength td").eq(i).addClass("password-meter-" + rating.message);
            }
        }
        if(rating.rate > 1) {
            $(".new-password-repeat").removeAttr("disabled");
        } else {
            $(".new-password-repeat").attr("disabled", "disabled");
        }
        if(rating.messageKey && rating.messageKey != undefined) {
            showError(rating.messageKey);
        } else {
            showError("");
        }
    });

    $(".pass-enter").live("click", function() {
        $(this).prop('disabled', true);
        reloadPage($(".form-signin").attr("rel"));
    });
}

