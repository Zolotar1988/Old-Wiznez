jQuery(document).ready(function ($) {

    var i = $('.zone_ui_dynamic').size() + 1;

    $('#flowboard_zones_add').click(function () {
        $('<div class="zone_ui_div"><input type="text" class="text zone_ui_dynamic" name="zone_ui_dynamic[]" /></div>').fadeIn('slow').appendTo('#flowboard_zones_ui');
        i++;
        $('.zone_ui_dynamic:last').focus();
    });

    $('#flowboard_zones_remove').click(function () {
        if ( i > 1 ) {
            $('.zone_ui_div:last').remove();
            i--;
            $('.zone_ui_dynamic:last').focus();
        }
    });

    $('#flowboard_zones_reset').click(function () {
        while ( i > 1 ) {
            $('.zone_ui_div:last').remove();
            i--;
        }
    });

});
