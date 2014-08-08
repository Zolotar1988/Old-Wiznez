jQuery(document).ready(function($) {
	
			jQuery("input[type=checkbox].switch").each(function() {
				// Insert switch
				jQuery(this).before('<span class="switch"><span class="background" /><span class="mask" /></span>');
				 //Hide checkbox
				jQuery(this).hide();
				if (!jQuery(this)[0].checked) jQuery(this).prev().find(".background").css({left: "-49px"});
				if (jQuery(this)[0].checked) jQuery(this).prev().find(".background").css({left: "-2px"});	
			});
			// Toggle switch when clicked
			jQuery("span.switch").click(function() {
				// Slide switch off
				if (jQuery(this).next()[0].checked) {
					jQuery(this).find(".background").animate({left: "-49px"}, 200);
				// Slide switch on
				} else {
					jQuery(this).find(".background").animate({left: "-2px"}, 200);
				}
				// Toggle state of checkbox
				jQuery('#').attr('checked', true);
				jQuery(this).next()[0].checked = !jQuery(this).next()[0].checked;
				
					if (jQuery("#easmedia_metabox_media_video_size").is(':checked')) {
					jQuery('#vidcustomsize').hide("slow");
					} else {
					jQuery('#vidcustomsize').show("slow");	
					}
					
					if (jQuery("#emgtinymce_custom_columns").is(':checked')) {
					jQuery('#customcolumns').show("slow");
					} else {
					jQuery('#customcolumns').hide("slow");	
					}	
					
					if (jQuery("#emgtinymce_custom_sz").is(':checked')) {
					jQuery('#mediacustomsize').show("slow");
					} else {
					jQuery('#mediacustomsize').hide("slow");	
					}
					
					if (jQuery("#emgtinymce_custom_align").is(':checked')) {
					jQuery('#customalign').show("slow");
					} else {
					jQuery('#customalign').hide("slow");	
					}					
						
					if (jQuery("#emgtinymce_custom_style").is(':checked')) {
					jQuery('#mediacustomstyle').show("slow");
					} else {
					jQuery('#mediacustomstyle').hide("slow");	
					}						
						
			});
			
});