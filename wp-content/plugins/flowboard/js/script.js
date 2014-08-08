/* jQuery script file for FlowBoard */

jQuery(document).ready(function () {
	/* This code is executed after the DOM has been completely loaded */

	var tmp;

	jQuery(".flowboard_zone").droppable({ accept:".note",
		activeClass                               :"ui-state-hover",
		hoverClass                                :"ui-state-active",
		drop                                      :function (event, ui) {
			var status = jQuery(this).attr('name');
			var id = jQuery(ui.helper[0]).find('span.data').html();
			jQuery('#flowboard_note_' + id).find('span.status').html(status);
			jQuery.get(FlowBoardAjax.ajaxurl, {
				action:'flowboard_update_position',
				x     :ui.position.left,
				y     :ui.position.top,
				z     :zIndex,
				status:ui.helper.find('span.status').html(),
				id    :parseInt(ui.helper.find('span.data').html())
			});
		}
	});

	jQuery('.note').each(function () {
		/* Finding the biggest z-index value of the notes */
		tmp = jQuery(this).css('z-index');
		if (tmp > zIndex) zIndex = tmp;
	});

	/* A helper function for converting a set of elements to draggables: */
	make_draggable(jQuery('.note'));

	jQuery('.note').click(function (event) {

		var note_id = jQuery(this).find('span.data').html();
		tb_show('Edit note', FlowBoardAjax.ajaxurl + '?action=flowboard_note_form&height=550&width=600&id=' + note_id);

	});

	jQuery('.pr-body,.pr-author').live('keyup', function (e) {
		if (!this.preview)
			this.preview = jQuery('.previewNote');
		this.preview.find(jQuery(this).attr('class').replace('pr-', '.')).html(jQuery(this).val().replace(/<[^>]+>/ig, ''));
	});

	jQuery('.pr-estimate,.pr-timeleft').live('keyup', function (e) {
		if (!this.preview)
			this.preview = jQuery('.previewNote');
		this.preview.find('.time').html(jQuery('.pr-timeleft').val().replace(/<[^>]+>/ig, '') + '/' + jQuery('.pr-estimate').val().replace(/<[^>]+>/ig, ''));
	});

	jQuery('.color').live('click', function () {
		jQuery('.previewNote').removeClass('yellow green blue purple orange pink').addClass(jQuery(this).attr('class').replace('color', ''));
		jQuery('.previewNote span.color').text(jQuery(this).attr('class').replace('color ', ''));
	});

	jQuery('.numbersOnly').keyup(function () {
		this.value = this.value.replace(/[^0-9\.]/g, '');
	});

	//  make_editable();


	/* The submit button: */
	jQuery('#note-submit').live('click', function (e) {

		if (jQuery('#noteData .pr-body').val().length < 1) {
			alert("The note text is too short!");
			return false;
		}

		if (jQuery('#noteData .pr-author').val().length < 1) {
			alert("You haven't entered your name!");
			return false;
		}

		var _id = jQuery('.previewNote span.data').first().text();

		var data = {
			action       :'flowboard_insert_note',
			'zindex'     :++zIndex,
			'body'       :jQuery('#noteData .pr-body').val(),
			'author'     :jQuery('#noteData .pr-author').val(),
			'board'      :jQuery('#noteData .pr-board').val(),
			'id'         :_id,
			'color'      :jQuery('.previewNote span.color').first().text(),
			'estimate'   :jQuery('#noteData .pr-estimate').val(),
			'status'     :jQuery('#noteData .pr-status').val(),
			'timeleft'   :jQuery('#noteData .pr-timeleft').val(),
			'postcontent':jQuery('#noteData .pr-postcontent').val(),
			'add_link'   :jQuery('#noteData .pr-addlink').is(':checked')
		};

		jQuery.ajaxSetup({async:false});

		/* Sending an AJAX POST request: */
		jQuery.post(FlowBoardAjax.ajaxurl, data, function (msg) {

			var flowboard_id = parseInt(msg);
			if (flowboard_id && flowboard_id > 0) {
				var _html = "";
				jQuery.get(FlowBoardAjax.ajaxurl + '?action=flowboard_note&id=' + flowboard_id, function (data) {

					var obj = jQuery('#flowboard_note_' + _id);

					if (jQuery(obj).length > 0) {
						jQuery(obj).replaceWith(data);
					}
					else {
						jQuery('#flowboardmain_' + jQuery('#noteData .pr-board').val()).append(data);
					}

					make_draggable(jQuery('.note'));
					jQuery('.note').click(function (event) {

						var note_id = jQuery(this).find('span.data').html();
						tb_show('Edit note', FlowBoardAjax.ajaxurl + '?action=flowboard_note_form&id=' + note_id);

					});

				});


			}
		});
		e.preventDefault();
		tb_remove();

	});

	jQuery('.note-form').live('submit', function (e) {
		e.preventDefault();
	});
});

var zIndex = 0;

function make_draggable(elements) {
	/* Elements is a jquery object: */
	elements.draggable({
		containment:'parent',
		start      :function (e, ui) {
			ui.helper.css('z-index', ++zIndex);
		},
		stop       :function (e, ui) {
		}
	});

}