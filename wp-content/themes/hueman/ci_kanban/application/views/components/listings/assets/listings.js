var listings = {

    admin: {

        create_new: function() {
            $.post('/listings/listings_actions_controller/create',
                $("#listing_create_new").serialize(),
                function(response) {
                    $("#profileListingContainerFor-new").html(response);
                    $("#profileListingRow-new-editable").hide();
                }
            );
        },

        update: function(listingId) {
            $.post('/listings/listings_actions_controller/edit?listingId=' + listingId,
                $("#listing_edit_" + listingId).serialize(),
                function(response) {
                    $("#profileListingContainerFor-" + listingId).html(response);
                    $("#profileListingRow-" + listingId + "-editable").hide();
                }
            );
        },

        openEdit: function(listingId)
        {
            $("#profileListingRow-" + listingId).hide();
            $("#profileListingRow-" + listingId + "-editable").show();
        }
    },

    listing: {

        apply: function(listingId) {
            $.get('/search/search_actions_controller/' + listingId,
                function(response) {
                    $("#search-response").html(response);
                }
            );
        },

        revoke: function(listingId) {
            if(confirm('Are you sure you wish to revoke your application?'))
            {
                $(".listing-rowItem-hasApplied-" + listingId).fadeOut('fast');

                $.get('/listings/listings_actions_controller/revoke?listingId=' + listingId,
                    function(response) {
                        $(".listingApplyBtnContainer-" + listingId).html(response);
                    }
                );
            }
        },

        edit_delete: function(listingId) {
            if(confirm('Are you sure you wish to delete this listing?'))
            {
                $.get('/listings/listings_actions_controller/edit_delete?listingId=' + listingId,
                    function(response) {
                        console.log(response);
//                    window.location.href = '/';
                    }
                );
            }
        }
    },

    user: {

        request_inviteToApply: function(userId) {
            $.get('/listings/listings_actions_controller/request_inviteToApply?userId=' + userId,
                function(response) {
                    window.location.href = '/profile/' + userId;
                }
            );
        }
    }
}