$(document).ready(function(){
    var idx = 0;
    var pageHeight = parseInt($('#content').height());
    $('nav > ul > li').each(function(){
        var jThis = $(this);
        (function(idx){
            jThis.click(function(){
                $('nav > ul > li').removeClass('active');
                $('.wrapper').css('marginTop', (-idx*pageHeight)+'px');
                jThis.addClass('active');
            });
        })(idx);
        idx++;
    });
    $('#test1').click(function(){
        var url = '/rewrite-'+Math.round(Math.random()*9999);
        $.ajax({
            dataType: "json",
            url: url,
            success: function(data, textStatus, jqXhr){
                $('#test-result').html(url+'<br />&gt; '+JSON.stringify(data));
            }
        });
    });
    $('#test2').click(function(){
        var url = '/rewrite-'+Math.round(Math.random()*9999)+'-'+Math.round(Math.random()*9999);
        $.ajax({
            dataType: "json",
            url: url,
            success: function(data, textStatus, jqXhr){
                $('#test-result').html(url+'<br />&gt; '+JSON.stringify(data));
            }
        });
    });
    $('#test3').click(function(){
        var url = '/rewrite-'+Math.round(Math.random()*9999)+'-'+Math.round(Math.random()*9999)+'-'+Math.round(Math.random()*9999);
        $.ajax({
            dataType: "json",
            url: url,
            success: function(data, textStatus, jqXhr){
                $('#test-result').html(url+'<br />&gt; '+JSON.stringify(data));
            }
        });
    });   