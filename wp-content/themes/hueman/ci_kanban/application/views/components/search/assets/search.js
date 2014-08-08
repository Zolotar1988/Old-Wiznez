var search = {

    run: function(whichSearch) {
        $.post('/search/search_actions_controller/' + whichSearch,
            $("#search_parameters").serialize(),
            function(response) {
                $("#search-response").html(response);
            }
        );
    }
}