jQuery.each(jQuery("#menu-menu1 .fontawesome-text"), function (index, item) {
    var selector = jQuery(item);
    if(selector.text().length < 3) selector.text('');
});