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

            },
            error: function(){
                alert("Произошла ошибка при загрузке данных.");
            }
        });
        return false;
    });

    $(".b-news-delete").live("click", function() {
        var news_id = $(this).closest('tr').attr('id');
        if(confirm("Удалить новость ID " + news_id + " ?")) {
            $.ajax({
                type: "POST",
                async: false,
                url: yii.urls.base + "/news/news/delete",
                data: {
                    news_id: news_id
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
});