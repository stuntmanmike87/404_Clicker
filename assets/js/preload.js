import $ from 'jquery';

$(document).ready(function(){
    $body = $('body');
    $body.addClass('loader')
    
});
$(window).load(function(){
    $('loader').fadeOut("slow");
    $('body').removeClass('loader');
});