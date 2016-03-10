var is_loading = false;
var surplus = $('#surplus');
$(window).scroll(function(){
    //console.info($(document).height() - $(this).scrollTop() - $(this).height());
    if ($(document).height() - $(this).scrollTop() - $(this).height()<50){
        if(surplus.val() == 0 || is_loading){
            return false;
        }

        var page = $('#page').val();
        is_loading = true;
        $.get(
            window.location,
            {page:page,random:Math.random()},
            function(resp){

                if(resp.status == '0'){
                    surplus.val(parseInt(surplus.val())-resp.list_total);
                    $("#item-wrap").append(resp.html);
                    $('#page').val(resp.page);
                } else {
                    surplus.val(0);
                }
            },
            'json'
        ).always(function () {
            is_loading = false;
        });
    }
});