(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

/*
window.onload = function() {
    var btn = (jQuery)("#wpcf7_block_analyze_btn");
    btn.html(`<a onclick="spcf_open_request_form();return false;" class="button-primary" name="wpcf7_block_analyze">Yes please</a>`);
};
*/

function spcf_open_request_form(){
    var d = (jQuery)("#spcf7_plugin_page");
    
    d.append(
        `<div class="analyze-request-form">
            <div class="request-form-wrap">
                <div class="request-form-field-wrap flex-column-center">
                    <h2>Log file analyze request</h2>
                    <small>Submit your log file link for our free analysis, and we'll provide you with a helpful report and suggestions. Your log file must be a minimum of 500kb.<br>
                    Please note that depending on our workload and the volume of requests, it may take a few days to a week.</small>
                </div>
                <div class="request-form-field-wrap">
                    <hr>
                </div>
                <div class="request-form-field-wrap">
                    <label for="request-form-email-id">Your email</label>
                    <input type="text" name="request-form-email" id="request-form-email-id" class="" placeholder="we need your email to send back our report">
                </div>
                <div class="request-form-field-wrap">
                    <label for="request-form-path-id">Log file full URL</label>
                    <input type="text" name="request-form-path" id="request-form-path-id" value="`+wpcf7_block_log_filename+`"
                        class="" placeholder="enter the url (including http/https) of the log file you want us to analyze">
                </div>
                <div class="request-form-field-wrap request-form-field-wrap-buttons">
                    <a onclick="spcf_close_request_form();return false;" class="button-primary" name="request_cancel">Cancel</a>
                    <button type="submit" class="button-primary" name="send_request" value="SendRequest">Send information</button>
                </div>
            </div>
        </div>`
    );
    
    return this;
}

function spcf_close_request_form(){
    (jQuery)(".analyze-request-form").remove();
}

function spcf_submit_request_form(){
    var form = document.getElementById('analyze-request-form');
    form.submit();
}

