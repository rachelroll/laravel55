$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function(){
    $("#sub").click(function(){
        var val = $('#search').val();
        var url = '/posts/search' + '?query=' + val;
        console.log(val);
        window.location.href = url;
    });
});

$(".like-button").click(function(event)
{
    var target = $(event.target);
    var current_like = target.attr('like-value');
    var user_id = target.attr("like-user");
    if(current_like == 1) {
        //取消关注
        $.ajax({
            url: "/user/" + user_id + "/unfan",
            method: "POST",
            dataType: "json",
            success: function(data) {
                if(data.error != 0) {
                    alert(data.msg);
                    return;
                }
                target.attr("like-value", 0);
                target.text("关注");
            }
        })
    }else {
        //关注
        $.ajax({
            url: "/user/" + user_id + "/fan",
            method: "POST",
            dataType: "json",
            success: function(data) {
                if(data.error != 0) {
                    alert(data.msg);
                    return;
                }
                target.attr("like-value", 1);
                target.text("关注");
            }
        })
    }

})