var hasChanged = false;

function confirmExit()
{
	var mce = typeof(tinyMCE) != 'undefined' ? tinyMCE.activeEditor : false;
	if(hasChanged || (mce && !mce.isHidden() && mce.isDirty() ))
		return "You have unsaved changes. Proceed anyway?";
}
window.onbeforeunload = confirmExit;

function substr_count(mainString, subString){
	var re = new RegExp(subString, 'g');
	if(!mainString.match(re) || !mainString || !subString)
	return 0;
	var count = mainString.match(re);
	return count.length;
}

function str_word_count(s){
	if(!s.length)
	return 0;
	s = s.replace(/(^\s*)|(\s*$)/gi,"");
	s = s.replace(/[ ]{2,}/gi," ");
	s = s.replace(/\n /,"\n");
	return s.split(' ').length;
}

function countTags(s){
	if(!s.length)
		return 0;
	return s.split(',').length;
}

function post_has_errors(title,content,bio,category,tags, fimg){
	var error_string = '';
	if(fep_rules.check_required == false)
		return false;

	if((fep_rules.min_words_title !=0 && title==='') || (fep_rules.min_words_content !=0 && content==='') || (fep_rules.min_words_bio !=0 && bio==='') || category==-1 || (fep_rules.min_tags !=0 &&  tags==='') )
		error_string = 'You missed one or more required fields</br>';
	
	var stripped_content = content.replace(/(<([^>]+)>)/ig,"");
	var stripped_bio = bio.replace(/(<([^>]+)>)/ig,"");

	if ( title != '' && str_word_count(title) < fep_rules.min_words_title )
        error_string += 'The title is too short<br/>';
	if ( content != '' && str_word_count( title ) > fep_rules.max_words_title )
        error_string += 'The title is too long<br/>';
    if ( content != '' && str_word_count( stripped_content ) < fep_rules.min_words_content )
        error_string += 'The article is too short<br/>';
    if ( str_word_count( stripped_content ) > fep_rules.max_words_content )
        error_string += 'The article is too long<br/>';
	if ( bio != -1 && bio != '' && str_word_count( stripped_bio ) < fep_rules.min_words_bio )
        error_string += 'The bio is too short<br/>';
    if ( bio != -1 && str_word_count( stripped_bio ) > fep_rules.max_words_bio )
        error_string += 'The bio is too long<br/>';
	if ( substr_count( content, '</a>' ) > fep_rules.max_links )
        error_string += 'There are too many links in the article body<br/>';
	if ( substr_count( bio, '</a>' ) > fep_rules.max_links_bio )
        error_string += 'There are too many links in the bio<br/>';
	if ( tags != '' && countTags(tags) < fep_rules.min_tags )
		error_string += 'You haven\'t added the required number of tags<br/>';
	if ( countTags(tags) > fep_rules.max_tags )
		error_string += 'There are too many tags<br/>';
	if(fep_rules.thumbnail_required && fep_rules.thumbnail_required == 'true' && fimg == -1)
		error_string += 'You need to choose a featured image<br/>';

	if ( error_string == '' )
		return false;
	else
		return '<h2>Your submission has errors. Please try again!</h2>'+error_string;
}

jQuery(document).ready(function($){
    $("input, textarea, #fep-post-content").keydown(function () { hasChanged = true; });
	$("select").change(function(){ hasChanged = true; });
	$("td.post-delete a").click(function(event) {
		var id 				= $(this).siblings('.post-id').first().val();
		var nonce 			= $('#fepnonce_delete').val();
		var loadimg 		= $(this).siblings('.fep-loading-img').first();
		var row 			= $(this).closest('.fep-row');
		var message_box 	= $('#fep-message');
		var post_count 		= $('#fep-posts .count');
		var confirmation 	= confirm('Are you sure?');
		if(!confirmation)
			return;
		$(this).hide();
		loadimg.show().css({'float':'none','box-shadow':'none'});
		$.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 'fep_delete_posts',
				post_id: id,
				delete_nonce: nonce
			},
			success:function(data, textStatus, XMLHttpRequest){
				var arr = $.parseJSON(data);
				message_box.html('');
				if(arr.success){
					row.hide();
					message_box.show().addClass('success').append(arr.message);
					post_count.html(Number(post_count.html())-1);
				}
				else{
					message_box.show().addClass('warning').append(arr.message);
				}
				if( message_box.offset().top < $(window).scrollTop() ){
					$('html, body').animate({ scrollTop: message_box.offset().top-10 }, 'slow');
				}
			},
			error: function(MLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
		event.preventDefault();
	});
	$("#fep-submit-post.active-btn").on('click', function() {
		tinyMCE.triggerSave();
		var title 			= $("#fep-post-title").val();
		var content 		= $("#fep-post-content").val();
		var bio 			= $("#fep-about").val();
		var category 		= $("#fep-category").val();
		var tags 			= $("#fep-tags").val();
		var pid 			= $("#fep-post-id").val();
		var fimg 			= $("#fep-featured-image-id").val();
		var nonce 	 		= $("#fepnonce").val();
		var message_box 	= $('#fep-message');
		var form_container	= $('#fep-new-post');
		var submit_btn 		= $('#fep-submit-post');
		var load_img 		= $("img.fep-loading-img");
		var submission_form = $('#fep-submission-form');
		var post_id_input 	= $("#fep-post-id");
		var errors 			= post_has_errors(title, content, bio, category, tags, fimg);
		if( errors ){
			if( form_container.offset().top < $(window).scrollTop() ){
			$('html, body').animate({ scrollTop: form_container.offset().top-10 }, 'slow'); }
			message_box.removeClass('success').addClass('warning').html('').show().append(errors);
			return;
		}
		load_img.show();
		submit_btn.attr("disabled", true).removeClass('active-btn').addClass('passive-btn');
		$.ajaxSetup({cache:false});
		$.ajax({
			type: 'POST',
			url: fepajaxhandler.ajaxurl,
			data: {
				action: 			'fep_process_form_input',
				post_title: 		title,
				post_content: 		content,
				about_the_author: 	bio,
				post_category: 		category,
				post_tags: 			tags,
				post_id: 			pid,
				featured_img: 		fimg,
				post_nonce: 		nonce
			},
			success:function(data, textStatus, XMLHttpRequest){
				hasChanged = false;
				var arr = $.parseJSON(data);
				if(arr.success){
					submission_form.hide();
					post_id_input.val(arr.post_id);
					message_box.removeClass('warning').addClass('success');
				}
				else
					message_box.removeClass('success').addClass('warning');
				message_box.html('').append(arr.message).show();
				if( form_container.offset().top < $(window).scrollTop() ){
					$('html, body').animate({ scrollTop: form_container.offset().top-10 }, 'slow');
				}
	            load_img.hide();
				submit_btn.attr("disabled", false).removeClass('passive-btn').addClass('active-btn');
			},
			error: function(MLHttpRequest, textStatus, errorThrown){
				alert(errorThrown);
			}
		});
	});
	$('body').on('click', '#fep-continue-editing', function(e){
		$('#fep-message').hide();
		$('#fep-submission-form').show();
		e.preventDefault();
	});
	$('#fep-featured-image a#fep-featured-image-link').click(function(e){
		e.preventDefault();
		custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
		custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            jQuery('#fep-featured-image input#fep-featured-image-id').val(attachment.id);
	        $.ajax({
				type: 'POST',
				url: fepajaxhandler.ajaxurl,
				data: {
					action: 'fep_fetch_featured_image',
					img: attachment.id
				},
				success:function(data, textStatus, XMLHttpRequest){
					$('#fep-featured-image-container').html(data);
					hasChanged = true;
				},
				error: function(MLHttpRequest, textStatus, errorThrown){
					alert(errorThrown);
				}
			});
        });
		custom_uploader.open();
	});
});