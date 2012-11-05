jQuery(document).ready(function() { 
    var options = { 
        target:        null,
        beforeSubmit:  null,
        success: function() { 
            jQuery('#success').fadeIn('slow').delay(2000).fadeOut('slow'); 
        } 
    }; 
 
    jQuery('#wp-notes').ajaxForm(options); 

}); 