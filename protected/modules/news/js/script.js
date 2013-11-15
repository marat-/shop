$(function() {
    $("#b-create-news, .b-news-update").live("click", function() {
        var news_id = $(this).closest('tr').attr('id');
        $.ajax({
            type: "POST",
            async: false,
            url: yii.urls.base + "/news/news/getNewsForm",
            data: {
                news_id: news_id
            },
            dataType: "html",
            success: function(data){
                $(".news-container").empty();
                $(".news-container").html(data);
                $('#news-create-dialog').modal();
                $('#news-create-dialog').on('hidden', function () {
                    $("body").undelegate("#saveNews","click");
                })
                rescaleModalWindow('#news-create-dialog');

            },
            error: function(){
                alert("Произошла ошибка при загрузке данных.");
            }
        });
        return false;
    });

    $(".remove-image").live("click", function() {
        $(this).parent().fadeOut(function() {
            $(this).find("a, img").remove();
            $(this).find("input:hidden").val("");
            $(this).show();
        });
    });

    $(".remove-image-from-gallery").live("click", function() {
        $(this).parent().fadeOut(function() {
            $(this).remove();
        });
    });

    $(".b-news-delete, .b-news-restore").live("click", function() {
        var action = $(this).hasClass('b-news-delete') ? 1 : 0;
        var news_id = $(this).closest('tr').attr('id');
        if(confirm((action ? "Удалить" : "Восстановить") + " новость ID " + news_id + " ?")) {
            $.ajax({
                type: "POST",
                async: false,
                url: yii.urls.base + "/news/news/delete",
                data: {
                    news_id: news_id,
                    action: action
                },
                dataType: "html",
                success: function(data){
                    $("#news-grid").yiiGridView("update", {});
                },
                error: function(){
                    alert("Произошла ошибка при загрузке данных.");
                }
            });
        }
        return false;
    });

    $(".b-news-view").live("click", function() {
        var news_id = $(this).closest('tr').attr('id');
        $.ajax({
            type: "POST",
            async: false,
            url: yii.urls.base + "/news/news/getNewsView",
            data: {
                news_id: news_id
            },
            dataType: "html",
            success: function(data){
                $(".news-container").empty();
                $(".news-container").html(data);
                $('#news-view-dialog').modal();
                rescaleModalWindow('#news-view-dialog');
            },
            error: function(){
                alert("Произошла ошибка при загрузке данных.");
            }
        });
        return false;
    });

    $("#close-news-dialog").live("click", function() {
        removeCLEditorInstances();
    });
});

function rescaleModalWindow(sel){
    var size = {width: $(window).width() , height: $(window).height() }
    /*CALCULATE SIZE*/
    var offset = 20;
    var offsetBody = 150;
    $(sel).css('height', size.height - offset );
    $('.modal-body').css('height', size.height - (offset + offsetBody));
    $('.modal-body').css('max-height', size.height - (offset + offsetBody));
    $(sel).css('top', 0);

    $(sel).css('width', size.width * 0.4);
    $(sel).css('margin-left', size.width * 0.2 * -1);
}