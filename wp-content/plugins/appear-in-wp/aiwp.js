/**
 * Theme cards for displaying excerpt and post content.
 */
jQuery(document).ready(function( $ ) {

	API.isAppearinCompatible(function (data) {
		// check if roomname is define in URI
		var aiRoom = lookToURI( 'appear-in' );
		var aiRef = lookToURI( 'aiwp-ref' );

		// if webRTC not supported show incompatibility message and hide room type selection
		// otherwise, if room set in URI, set up room
		// otherwise, do nothing
        if ( !data.isSupported ) {
            $('#appearin-incompatibility').show();
            $('#aiwp-room-type-selection').hide();
        } else if ( '' != aiRoom ) {

        	// hide room type selection
        	$('#aiwp-room-type-selection').hide();

        	// launch room
        	launchAppearInRoom( aiRoom );

        	// respond to server with accepted invite
        	$.post(ajaxurl, {
        		action: 'aiwp_direct_session',
        		aiwp_room: aiRoom,
        		aiwp_ref: aiRef,
        		aiwp_security: $('#appearin-room').data('security')
        	});

        } // end if
    });

	function randomStringGenerator() {
        // predefine the alphabet used
        var alphabet = 'qwertyuiopasdfghjklzxcvbnm1234567890';

        // set the length of the string
        var stringLength = 30;

        // initialize the room name as an empty string
        var randomString = '';

        // repeat this 30 times
        for ( var i=0; i<stringLength; i++) {
            // get a random character from the alphabet
            var character = alphabet[Math.round(Math.random()*(alphabet.length-1))];

            // add the character to the roomName
            randomString = randomString + character;
        }

        // return the result
        return randomString;
    }

	// Handle Room Selection
	$('#aiwp-select-public-room,#aiwp-select-private-room,#aiwp-select-post-room').click( function () {
			
		var roomType = $(this).data('room-type');
		var roomInvites = $(this).data('room-invites');
		if ( 'disabled' == roomInvites ) {
			if ( 'post' == roomType ) {
				var roomURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
				launchAppearInRoom( roomURL, 'post' );
			} else if ( 'public' == roomType ) {
				var roomName = $('#appearin-room').attr('data-room-name');
				launchAppearInRoom( roomName, 'public' );
			} else if ( 'private' == roomType ) {
				var randomString = 'private-' + randomStringGenerator();
				launchAppearInRoom( randomString, 'private' );
			}
			
			$.post(ajaxurl, {
				action: 'aiwp_session',
				aiwp_room_type: roomType,
				aiwp_security: $('#appearin-room').data('security') });
		} else if ( 'enabled' == roomInvites ) {
			$(this).hide();
			$('#aiwp-' + roomType + ' span').show().delay( 4250 ).slideUp( 500 );
			$('#aiwp-' + roomType + '-invite-form').show();
		}

	});

	// set value attribute of input to inputted text when focus leaves input field
	$(' #aiwp-post input[type="text"], #aiwp-public input[type="text"], #aiwp-private input[type="text"]').blur( function() {
		var curval = $(this).val();
		$(this).attr('value',curval);
	});

	// show next email text field when user begins typing in current email field
	$(' #aiwp-post input[type="text"], #aiwp-public input[type="text"], #aiwp-private input[type="text"]').keypress( function() {
		$(this).next().show();
	});

	// Handle invitations
	$('#aiwp-send-post-invites,#aiwp-send-public-invites,#aiwp-send-private-invites').click( function() {
		var roomType = $(this).data('room-type');
		var roomURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
		if ( 'post' == roomType ) {
			var roomName = roomURL;
		} else if ( 'public' == roomType ) {
			var roomName = $('#appearin-room').data('room-name');
		} else if ( 'private' == roomType ) {
			var roomName = 'private-' + randomStringGenerator();
		}
		$.post(ajaxurl,
			{	action: 'aiwp_invite',
				aiwp_room_url: roomURL,
				aiwp_room: roomName,
				aiwp_room_type: roomType,
				aiwp_username: $('#aiwp-' + roomType + '-username').val(),
				aiwp_email: $('#aiwp-' + roomType + '-email').val(),
				aiwp_invite_1: $('#aiwp-' + roomType + '-invite-1').val(),
				aiwp_invite_2: $('#aiwp-' + roomType + '-invite-2').val(),
				aiwp_invite_3: $('#aiwp-' + roomType + '-invite-3').val(),
				aiwp_invite_4: $('#aiwp-' + roomType + '-invite-4').val(),
				aiwp_invite_5: $('#aiwp-' + roomType + '-invite-5').val(),
				aiwp_invite_6: $('#aiwp-' + roomType + '-invite-6').val(),
				aiwp_invite_7: $('#aiwp-' + roomType + '-invite-7').val(),
				aiwp_security: $('#appearin-room').data('security'),
			}, function (response) {
				// If the server returns '1', then we can mark this post as read, so we'll hide the checkbox
                // container. Next time the user browses the index, this post won't appear
                if (1 === parseInt(response)) {

                    launchAppearInRoom( roomName, roomType );

                    // respond to server with new session
                    $.post(ajaxurl, {
                    	action: 'aiwp_session',
                    	aiwp_room_type: roomType,
                    	aiwp_security: $('#appearin-room').data('security')
                    	});

                // Otherwise, let's alert the user that there was a problem. In a larger environment, we'd
                // want to handle this more gracefully.
                } else {

                    alert("Your invites were not sent. Please, check the addresses and try again.");

                } // end if/else
			})
		});

	function launchAppearInRoom( randomString, roomType ) {
		var roomName = 'https://appear.in/' + randomString + '?lite';
		var roomURL = window.location.protocol + "//" + window.location.host + window.location.pathname;
		// set the iframe source to load the room
		var iframe = document.getElementById('appearin-room');
		iframe.setAttribute('src', roomName);

		$('#aiwp-room-type-selection').hide();
		$('#appearin-current-' + roomType).show();
		$('#appearin-room').css('height','700px');
		$('#appearin-room-label').html(roomURL+'?appear-in='+roomName+'&aiwp-ref=invite');

	}

	function lookToURI(name){
		if(name=(new RegExp('[?&]'+encodeURIComponent(name)+'=([^&]*)')).exec(location.search)) {
			return decodeURIComponent(name[1]);
		} else {
			return '';
		}
	}

});
