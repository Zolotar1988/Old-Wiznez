jQuery(document).ready(function($) {
    var easymediaList = $('#easymedia_list');
    
    easymediaList.sortable({
        update: function(event, ui) {
            
            opts = {
                url: ajaxurl,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'easymedia_sort',
                    order: easymediaList.sortable('toArray').toString() 
                },
                success: function(response) {
                    return;
                },
                error: function(xhr,textStatus,e) {
                    alert('There was an error saving the update.');
                    return;
                }
            };
            $.ajax(opts);
        }
    });
});