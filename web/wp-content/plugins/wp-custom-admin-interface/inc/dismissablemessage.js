jQuery(document).ready(function ($) {

    
    $('.wrap').on("click","#custom-admin-notice .notice-dismiss",function(event) {
        event.preventDefault();        
        

        var userId = $(this).parent().attr('data');
        var nonce = $(this).parent().attr('data-nonce');

        //do request    
        var data = {
            'action': 'dismiss_message',
            'userId': userId,
            'nonce': nonce,
        };


        jQuery.post(ajaxurl, data, function (response) {

        });
        
    });
    

});
