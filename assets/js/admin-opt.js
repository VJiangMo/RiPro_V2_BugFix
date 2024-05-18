jQuery(document).ready(function($) {
    $(document).on('click', ".rest_mpweixin_menu", function(event) {
        event.preventDefault()
        $.post(ri_opt_js.admin_url, {
            "action": "rest_mpweixin_menu",
        }, function(data) {
            // console.log(data)
            if (data.errcode == 0) {
                alert('公众号菜单更新成功');
            } else {
                alert(data.errmsg);
            }
        });
    });


    $("#theme_act_btn").click(function(t){var a=$(this),e=a.data("text");a.text("Loading..."),a.attr("disabled",!0),$.post(ri_opt_js.admin_url,{action:"theme_ajax_activ",id:$("#theme_lic_id").val(),key:$("#theme_lic_key").val()},function(t){a.text(t.msg),setTimeout(function(){a.text(e),a.attr("disabled",!1),1==t.status&&confirm("激活成功，是否刷新当前页面？")&&location.reload()},2e3)})});

});