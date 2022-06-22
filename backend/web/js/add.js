$(document).ready(function() {
    if($( window ).width() < 767) {
        $('table').parent().css('overflow','auto');
        $('table').parent().css('width','100%');
    }

    $('.none-name').attr('name', '');
});

function loadAjaxNotImg(url, data, div) {
    $.ajax({
        url: url,
        data: data,
        success: function(result){
            div.html(result);
        }
    });
}

function loadAjax(url, data, div) {
    div.html('<img style="padding:5px 10px;" src="'+baseUrl+'images/ajax-loader.gif" />');
    $.ajax({
        url: url,
        data: data,
        success: function(result){
            div.html(result);
        }
    });
}

function loadAjaxFuction(url, data, div, funct) {
    div.html('<img style="padding:5px 10px;" src="'+baseUrl+'images/ajax-loader.gif" />');
    $.ajax({
        url: url,
        data: data,
        success: function(result){
            window[funct](result);
        }
    });
}

function loadAjaxPostFuction(url, data, div, funct) {
    div.html('<img style="padding:5px 10px;" src="'+baseUrl+'images/ajax-loader.gif" />');
    $.ajax({
        url: url,
        data: data,
        type: 'POST',
        success: function(result){
            window[funct](result);
        }
    });
}

function loadAjaxPost(url, data, div) {
    div.html('<img style="padding:5px 10px;" src="'+baseUrl+'images/ajax-loader.gif" />');
    $.ajax({
        url: url,
        data: data,
        type: 'POST',
        success: function(result){
            div.html(result);
        }
    });
}

function loadAjaxAppend(url, data, div) {
    $('body').append('<div id="fixed-loading-img" class="fixed"><div class="flex"><div class="child-flex"><img style="padding:5px 10px;" src="'+baseUrl+'images/ajax-loader.gif" /></div></div></div>');
    $.ajax({
        url: url,
        data: data,
        success: function(result){
            div.append(result);
            $('#fixed-loading-img').remove();
        }
    });
}

function formatMoney(a,c, d, t){
    var n = a, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = String(parseInt(n = Math.abs(Number(n) || 0).toFixed(c))), 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
};

$(document).ready(function () {
    $('select.select2.form-control').select2();
})