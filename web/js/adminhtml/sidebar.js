/**
 * Created by thinlt on 5/5/2016.
 */
jQuery(document).ready(function(){

    jQuery.sidebar = new Slideout({
        'panel': document.getElementById('panel'),
        'menu': document.getElementById('menu'),
        'padding': 256,
        'tolerance': 70
    });
    jQuery.sidebar.open();

    jQuery(window).resize(function() {
        if($(window).width() <= 768){
            if(jQuery.sidebar.isOpen()) jQuery.sidebar.close();
        }else{
            if(!jQuery.sidebar.isOpen()) jQuery.sidebar.open();
        }
    });

});
