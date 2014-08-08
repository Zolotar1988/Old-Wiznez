jQuery(document).ready(function($) {

//Show Dialogbox

	var sellval;
	
		jQuery("#emgtinymce_select_method").multiselect({
		multiple: false,
		header: "Order Media by",
		noneSelectedText: "Select an Option",
		selectedList: 1,
		header: false
	});

		jQuery("#emgtinymce_select_cat").multiselect({
		multiple: false,
		header: "Choose a category",
		noneSelectedText: "Select Category",
		selectedList: 1,
		header: false
	});
	
		jQuery("#emgtinymce_select_sing_media").multiselect({
		multiple: true,
		header: "Choose Media",
		noneSelectedText: "Select Media",
		selectedList: 1,
		header: true
	});
	
		jQuery("#select_custom_col").multiselect({
		multiple: false,
		header: "Select Columns",
		noneSelectedText: "Select Columns",
		selectedList: 1,
		header: false
	});	
	
		jQuery("#select_cus_align").multiselect({
		multiple: false,
		header: "Select Align",
		noneSelectedText: "Select Align",
		selectedList: 1,
		header: false
	});	
	
		jQuery("#select_cus_style").multiselect({
		multiple: false,
		header: "Select Style",
		noneSelectedText: "Select Style",
		selectedList: 1,
		header: false
	});		

		// load media items (AJAX)
		function emg_load_media_list(taxo) {
			var data = {
				action: 'emg_load_media_list',
				taxo: taxo
			};
			
			jQuery.post(ajaxurl, data, function(response) {
				jQuery('#emgtinymce_select_sing_media').html(response);
				
			});	
			
			return true;
		}	
	
jQuery("#emgtinymce_select_method").multiselect({
   click: function() {
	   jQuery("#emgtinymce_select_sing_media").multiselect("uncheckAll");
	   jQuery("#emgtinymce_select_cat").multiselect("uncheckAll");
   }
});			

// END LOAD MEDIA

	jQuery("body").delegate("#add_emg_shortcode_button","click",function(){	
		
			mg_H = 300;
			mg_W = 550;
			
		setTimeout(function() {
			tb_show( 'Easy Media Shortcode', '#TB_inline?height='+mg_H+'&width='+mg_W+'&inlineId=modal' );
			jQuery('#TB_window').css("height", mg_H);
			jQuery('#TB_window').css("width", mg_W);
			jQuery('#TB_window').css("top", ((jQuery(window).height() - mg_H) / 6) + 'px');
			jQuery('#TB_window').css("left", ((jQuery(window).width() - mg_W) / 4) + 'px');
			jQuery('#TB_window').css("margin-top", ((jQuery(window).height() - mg_H) / 6) + 'px');
			jQuery('#TB_window').css("margin-left", ((jQuery(window).width() - mg_W) / 4) + 'px');
			jQuery("#TB_window").css('height','auto');
			jQuery("#TB_ajaxContent").css('height','auto');
			
			jQuery("#emgtinymce_select_cat_div").hide();
			jQuery("#emgtinymce_select_sing_media_div").hide();
			jQuery("#emgtinymce_select_method").val('Select');
			jQuery("#emgtinymce_select_method").multiselect('refresh');
			jQuery("#select_custom_col").val('Select');
			jQuery("#select_custom_col").multiselect('refresh');
			jQuery("#select_cus_align").val('Select');
			jQuery("#select_cus_align").multiselect('refresh');
			jQuery('#emgtinymce_custom_columns').prop('checked', false);
			jQuery('#customcolumns').hide("slow");	
			jQuery('#custom_col_div span.switch').find(".background").animate({left: "-49px"}, 200);
			jQuery('#emgtinymce_custom_sz').prop('checked', false);
			jQuery('#mediacustomsize').hide("slow");	
			jQuery('#custom_size_div span.switch').find(".background").animate({left: "-49px"}, 200);
			jQuery('#emgtinymce_custom_align').prop('checked', false);
			jQuery('#customalign').hide("slow");
			jQuery('#mediacustomstyle').hide("slow");	
			jQuery('#custom_align_div span.switch').find(".background").animate({left: "-49px"}, 200);
			jQuery('#emgtinymce_custom_columns').prop('checked', false);
			jQuery('#emgtinymce_select_sing_media_div span.switch').find(".background").animate({left: "-49px"}, 200);
			jQuery('#emgtinymce_mark_asgallery').prop('checked', false);		
			jQuery('#custom_style_div span.switch').find(".background").animate({left: "-49px"}, 200);
			jQuery('#emgtinymce_custom_style').prop('checked', false);			
			jQuery("#emgtinymce_cus_width").val('');
			jQuery("#emgtinymce_cus_height").val('');

		}, 300);	
	});
		
	// Resizing thickbox window dynamically	
	jQuery(window).resize(function() {
		if(jQuery('#tinyform').is(':visible')) {
			jQuery('#tinyform').parent().parent().css("height", mg_H);
			jQuery('#tinyform').parent().parent().css("width", mg_W);	
			jQuery('#tinyform').parent().parent().css("top", ((jQuery(window).height() - mg_H) / 6) + 'px');
			jQuery('#tinyform').parent().parent().css("left", ((jQuery(window).width() - mg_W) / 4) + 'px');
			jQuery('#tinyform').parent().parent().css("margin-top", ((jQuery(window).height() - mg_H) / 6) + 'px');
			jQuery('#tinyform').parent().parent().css("margin-left", ((jQuery(window).width() - mg_W) / 4) + 'px');
		}
	});	
	
	
	// Show/Hide element
	jQuery("#emgtinymce_select_cat_div").hide();
	jQuery("#emgtinymce_select_sing_media_div").hide();
	
				jQuery("#emgtinymce_select_method").change(function(){
				var listidx = document.getElementById("emgtinymce_select_method").selectedIndex;
						
				if(listidx == '1'){
					jQuery("#emgtinymce_select_cat_div").slideDown("slow");
					jQuery("#emgtinymce_select_sing_media_div").slideUp("slow");
					}
				else if(listidx == '2'){
					jQuery("#emgtinymce_select_cat_div").slideUp("slow");
					jQuery("#emgtinymce_select_sing_media_div").slideDown("slow");
					
					}	
				else {
					jQuery("#emgtinymce_select_cat_div").slideUp("fast")
					jQuery("#emgtinymce_select_sing_media_div").slideUp("fast");
					}						
				});
					jQuery('#emgtinymce_select_method').change();	
					
	
//Get all selected values from the drop down list 
jQuery(function(){
    jQuery("#emgtinymce_select_sing_media").multiselect({
        close: function(event) {
			
			var array_of_checked_values = jQuery('#emgtinymce_select_sing_media').multiselect("getChecked").map(function(){
	return this.value;}).get().toString();
	jQuery('#thisresult').text(array_of_checked_values);
	sellval = null;
        	},
    	});
	});
	
	// add the shortcode to the post
	jQuery('#emg_insert_scrt').on("click", function () {
		
	if ( jQuery( "#emgtinymce_select_method" ).val() != '' && document.getElementById("emgtinymce_select_method").selectedIndex != '0' ) {
		var custcol;
		var custalgn;
					
		if ( jQuery("#emgtinymce_custom_columns").is(':checked') &&  jQuery("#select_custom_col").val() != '0' ) {
			custcol = ' col="'+jQuery("#select_custom_col").val()+'"';
			} else {
				jQuery('#customcolumns').hide("slow");
					custcol = '';	
					}
															
		if ( jQuery("#emgtinymce_custom_align").is(':checked') &&  document.getElementById("select_cus_align").selectedIndex != '0' ) {
			custalgn = ' align="'+jQuery("#select_cus_align").val().toLowerCase()+'"';
			} else {
				jQuery('#customalign').hide("slow");
					custalgn = '';	
					}								
		
		var medcat = jQuery('#emgtinymce_select_cat').val();
		var catcode = '[easy-media cat="'+medcat+'"'+custcol+custalgn+']';
		var sctype = document.getElementById("emgtinymce_select_method").selectedIndex;
		var medid = jQuery('#thisresult').text(); //medid = medid.substr(0,medid.length - 1);
		var medid = '[easy-media med="'+medid+'"'+custcol+custalgn+']';
		
		if ( sctype == '1' ) {
		if( jQuery('#wp-content-editor-container > textarea').is(':visible') ) {
			var val = jQuery('#wp-content-editor-container > textarea').val() + catcode;
			jQuery('#wp-content-editor-container > textarea').val(val);	
		}
		else {tinyMCE.activeEditor.execCommand('mceInsertContent', 0, catcode); }
		}
		
		else if ( sctype == '2' ) {

		if( jQuery('#wp-content-editor-container > textarea').is(':visible') ) {
			var val = jQuery('#wp-content-editor-container > textarea').val() + medid;
			jQuery('#wp-content-editor-container > textarea').val(val);	
		}
		else {tinyMCE.activeEditor.execCommand('mceInsertContent', 0, medid); }
		}

		tb_remove();
	}
	else {tb_remove();}
	});	
});