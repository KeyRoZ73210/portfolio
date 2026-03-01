<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * 
 * @since      1.0.0
 *
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/admin
 * @author     New York Software Lab
 * @link       https://nysoftwarelab.com
 */
class Spam_Protect_for_Contact_Form7_Admin {

    /**
     * The unique identifier of this plugin.
     */
    private $plugin_name;

    /**
     * The current version of the plugin.
     */
    private $version;

    /**
     * Request form submited true/false;
     */
    private $requested_analyze = false;

    /**
     * Constructor of the class.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // hook into contact form 7 form
        add_filter('wpcf7_editor_panels', array($this, 'spcf7_plugin_editor_panels'));

        // hook into contact form 7 admin form save
        add_action('wpcf7_after_save', array($this, 'spcf7_plugin_save_contact_form'));

        // hook notice function
        //add_action( 'admin_notices', array($this,'result_admin_notice__success'), 10, 3);
    }

    // hook into contact form 7 form
    public function spcf7_plugin_editor_panels($panels) {

        $new_page = array(
            'spcf7_plugin_page' => array(
                'title' => __("Antispam Settings", "contact-form-7-spam-blocker"),
                'callback' => array($this, 'spcf7_plugin_admin_post_settings')
            )
        );

        $panels = array_merge($panels, $new_page);
        return $panels;
    }

    public function spcf7_plugin_admin_post_settings($cf7) {
        $post_id = sanitize_text_field($_GET['post']);
        
        $wpcf7_block_email_list_val = get_post_meta($post_id, "_wpcf7_block_email_list", true);
        $wpcf7_block_email_domain = get_post_meta($post_id, "_wpcf7_block_email_domain", true);
        $wpcf7_block_top_domain = get_post_meta($post_id, "_wpcf7_block_top_domain", true);
        $wpcf7_protected_fields = get_post_meta($post_id, "_wpcf7_protected_fields", true);
        $wpcf7_block_words = get_post_meta($post_id, "_wpcf7_block_words", true);
        $wpcf7_block_shortlinks = get_post_meta($post_id, "_wpcf7_block_shortlinks", true);
        $wpcf7_block_logging = get_post_meta($post_id, "_wpcf7_block_logging", true);
        $wpcf7_block_log_filename = trim(get_post_meta($post_id, "_wpcf7_block_log_filename", true));
        $wpcf7_block_email_error_msg = get_post_meta($post_id, "_wpcf7_block_email_error_msg", true);

        $log_file_size = 0;
        $can_send_request = false;

        if ($wpcf7_block_log_filename != "") { $log_file_size = filesize("../wp-content/".$wpcf7_block_log_filename); }else{ $log_file_size = filesize("../wp-content/spcf_spam_block.log"); }
        if (empty($wpcf7_block_log_filename)) { $wpcf7_block_log_filename = "spcf_spam_block.log"; }

        $log_file_size_str = 0;
        if ($log_file_size > 0 && $log_file_size < 1024000){
            $log_file_size_str = round($log_file_size /1024, 2)." KB";
        }
        if ($log_file_size >= 1024000){
            $log_file_size_str = round( ($log_file_size /1024)/1024, 2)." MB";
        }
        if ($log_file_size >= 2097152){
            $can_send_request = true;
        }
        
        // Default error message
        if (empty($wpcf7_block_email_error_msg)) {
            $wpcf7_block_email_error_msg = 'We do not accept spam emails, ADs and other type of unwanted info. If this is a false block, please contact us with a different method.';
        }

        ?>
        <div class="main-wrap">
            <h1 class="" style="display: flex; justify-content: center;align-items: end;">Spam Protect for Contact Form 7&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<small>Version: <?php echo SPCF7_VERSION; ?></small></h1>
            <div>
                <hr>
                <small>
                    <p>In versions 1.2.0 and beyond, our system has been aggregating spam messages and corresponding IP addresses from over 8000 websites. With your invaluable support, including 5-star ratings, constructive feedback, and insightful comments, we are poised to elevate the capabilities of this plugin further.</p>
                    <p>Utilizing different technologies, we have enhanced our capacity to meticulously analyze log files and offer more refined support. This empowers us to provide users with increasingly sophisticated filtering options.</p>
                    <p>We extend our heartfelt gratitude for your contributions, which enable us to continually improve and expand our offerings. Thank you for being an integral part of our journey.</p>
                </small>
            </div><?php
            if ( get_post_meta($post_id, "_wpcf7_request_log_analyze", true)=="1" ) {
                update_post_meta($post_id, "_wpcf7_request_log_analyze", 0);?>
                <div class="spcf7-notice">
                    <p><strong><?php _e( 'Hooray!, your plea for assistance in the epic battle against spambots has been accepted! our team is more excited than a squirrel at a nut buffet to tackle this!', 'spam-protect-for-contact-form7' ); ?></strong></p>
                    <p><strong><?php _e( 'Thank you!', 'spam-protect-for-contact-form7' ); ?></strong></p>
                </div><?php
            }else{
                //echo get_post_meta($post_id, "_wpcf7_request_log_analyze", true)." ERROR";
            }?>
            <hr/>
            <fieldset>
                <div class="email-block-list">
                    <h3 class="blocker-7-setting">List the email addresses you wish to block.</h3>
                    <p><small class="blocker-7-setting-small">Enter email addresses separating each with a comma: email@domain1.com, email2@domail2.com</small></p>
                    <textarea name="wpcf7_block_email_list" id="wpcf7-block-email-list-id" cols="100" rows="4" 
                              class="large-text-cf7hk"  placeholder="Eg: example@spamdomain.com, example2@spamdomain2.com"><?php echo esc_html(trim($wpcf7_block_email_list_val)); ?></textarea>
                    <input type='hidden' name='cf7pp_post' value='<?php echo esc_html($post_id); ?>'>
                </div>
                <hr>
                <div class="email-domain-list">
                    <h3 class="blocker-7-setting second">List the email domains you wish to block.</h3>
                    <p><small class="blocker-7-setting-small">Enter the email domains separated by commas and preceded by '@': @domain1.com, @domain2.com</small></p>
                    <textarea name="wpcf7_block_email_domain" id="wpcf7-block-email-domain-id" cols="100" rows="4" 
                              class="large-text-cf7hk"  placeholder="Eg: @spamdomain.com, @spamdomain2.com"><?php echo esc_html(trim($wpcf7_block_email_domain)); ?></textarea>
                </div>
                <hr>
                <div class="email-TLD-list">
                    <h3 class="blocker-7-setting second">List the top-level email domains or countries you wish to block.</h3>
                    <p><small class="blocker-7-setting-small">List the top level email domains (TLD) or countries separating each with a comma: .xyz, .live, .tv, .buzz, .ru</small></p>
                    <textarea name="wpcf7_block_top_domain" id="wpcf7-block-TLD-id" cols="100" rows="4" 
                              class="large-text-cf7hk"  placeholder="Eg: .xyz, .live, .tv "><?php echo esc_html(trim($wpcf7_block_top_domain)); ?></textarea>
                </div>
                <div class="blank-field-prevention-list">
                    <h3 class="blocker-7-setting second">List the form fields you wish to protect from blank text submissions.</h3>
                    <p><small class="blocker-7-setting-small">">List the form field names you wish to protect from being submitted with blank text or white spaces by bots, separated by commas: your-email, your-message, your-subject.</small></p>
                    <textarea name="wpcf7_protected_fields" id="wpcf7-protected-fields-id" cols="100" rows="4" 
                              class="large-text-cf7hk"  placeholder="your-email, your-message, your-subject"><?php echo esc_html(trim($wpcf7_protected_fields)); ?></textarea>
                </div>
                <hr>
                <div class="blocked-words-list">
                    <h3 class="blocker-7-setting second">List the words or phrases you wish to block.</h3>
                    <p><small class="blocker-7-setting-small">Enter lowercase words or phrases (avoid using 2-letter words; instead, use specific words or phrases with 2 or more words), separating each with a comma: banned word, spam key words, other spam identifiers, etc.</small></p>
                    <textarea name="wpcf7_block_words" id="wpcf7-block-words-id" cols="100" rows="4" 
                              class="large-text-cf7hk"  placeholder="Eg: sexy girls, viagra, datingsite"><?php echo esc_html(trim($wpcf7_block_words)); ?></textarea>
                </div>
                <hr>
                <div class="block-shortlinks">
                    <h3 class="blocker-7-setting second">Block messages that contain shortlinks.</h3>
                    <p><small class="blocker-7-setting-small">Shortlinks are a common and very convenient method used by many bots and spammers. Select YES to block some of the most popular shortlink domains.</small></p>
                    <select id="wpcf7-block-shortlinks-id" name="wpcf7_block_shortlinks">
                        <option value="no"  <?php echo ($wpcf7_block_shortlinks=="no")  ? "selected" : ""; ?> >No</option>
                        <option value="yes" <?php echo ($wpcf7_block_shortlinks=="yes") ? "selected" : ""; ?> >Yes</option>
                    </select>
                </div>
                <hr>
                <div class="block-error-msg">
                    <h3 class="blocker-7-setting second">Customize your error message.</h3>
                    <p><small class="blocker-7-setting-small">Provide a general error message to display if any of the above filters are triggered in the form.</small></p>
                    <input type="text" name="wpcf7_block_email_error_msg" id="wpcf7-block-email-error-id" 
                           class="wpcf7-block-email-error-cls" placeholder="Your error message" value="<?php echo esc_html(trim($wpcf7_block_email_error_msg)); ?>">
                </div>
                <hr>
                <div class="block-logging">
                    <h3 class="blocker-7-setting second">Log failed messages.</h3>
                    <p><small class="blocker-7-setting-small">Enable logging for failed messages, which is useful for debugging and troubleshooting false-positive spam prevention.</small></p>
                    <select id="wpcf7-block-logging-id" name="wpcf7_block_logging">
                        <option value="yes" <?php echo ($wpcf7_block_logging=="yes") ? "selected" : ""; ?> >Yes</option>
                        <option value="no"  <?php echo ($wpcf7_block_logging=="no")  ? "selected" : ""; ?> >No</option>
                    </select>
                </div>
                <hr>
                <div class="block-error-msg">
                    <h3 class="blocker-7-setting second">Set your log file filename. <span><small>(optional)</small></span></h3>
                    <p><small class="blocker-7-setting-small">
                    Please specify the filename you prefer for storing the log. For instance, 'spcf_spam_block.log' (recommended), 'myform.log,' or '[random-name].log', extension must always be (.log). <br>
                    You may leave this field blank to use the default value 'spcf_spam_block.log'. You can utilize this field to manage different log files for multiple contact forms across your site. <br>
                    IMPORTANT: Ensure your server supports MIME file extensions for download or viewing, and ensure the file does not already exist or is being used by another plugin.
                    </small></p>
                    <input type="text" name="wpcf7_block_log_filename" id="wpcf7-block-log-filename-id" 
                           class="wpcf7-block-email-error-cls" placeholder="Log filename, default: spcf_spam_block.log" value="<?php echo esc_html(trim($wpcf7_block_log_filename)); ?>">
                </div>
                <hr>
                <div class="block-log-actions">
                    <!-- Download/ View -->
                    <div class="block-show-log block-boxed">
                        <?php
                        if (trim($wpcf7_block_log_filename)==""){
                            $wpcf7_block_download = "/wp-content/spcf_spam_block.log";
                        }else{
                            $wpcf7_block_download = "/wp-content/".trim($wpcf7_block_log_filename);
                        }?>
                        <div class="block-boxed-button-header"><h4 class="blocker-7-setting third text-center" >To download or open/view the log file click the button below.</h4></div>
                        <p><a class="button-primary" href="<?php echo $wpcf7_block_download; ?>" target="_blank">open/view Log file</a></p>
                    </div>
                    <!-- Clear log -->
                    <div class="block-delete-log block-boxed block-boxed-second">
						<div class="block-boxed-button-header">
							<h4 class="blocker-7-setting third text-center" >To erase/clear the log file click the erase button. Consider to request an analysis before you delete your log file.<br>Current file size: <?php echo $log_file_size_str; ?></h4>
						</div>
                        <p class=""><input type="submit" 
                            onclick="return confirm('Deleting the log file will result in the inability to review records of failed form submissions; however, it will gradually accumulate data in the future.\r\n\r\n'
                                +'Furthermore, we will be unable to analyze your log file if you send us a request. In this case, it is recommended to delete it after receiving our report.\r\n\r\n'
                                +'Are you certain about proceeding with this action?')" 
                            class="button-primary" name="wpcf7_block_log_erase" value="Erase log"></p>
                    </div>
                    <div class="block-report-log block-boxed block-boxed-second text-center">
                        <div class="block-boxed-button-header"><h4 class="blocker-7-setting third">Thanks for choosing Spam Protect for Contact Form 7 plugin.</h4></div>
                        <?php 
                            /*
                            if ($can_send_request){?>
                                <p class="" id="wpcf7_block_analyze_btn"></p><?php
                            }else{?>
                                <p class=""><div>Log file is too small</div></p><?php
                            }
                            */
                        ?>
                    </div>
                </div>
            </fieldset>
        </div>
        <?php
    }

    // hook into contact form 7 admin form save
    public function spcf7_plugin_save_contact_form($cf7) {
        $post_id = sanitize_text_field($_POST['cf7pp_post']);
        
        // Manual email list
        $wpcf7_block_email_list = sanitize_text_field($_POST['wpcf7_block_email_list']);
        update_post_meta($post_id, "_wpcf7_block_email_list", trim($wpcf7_block_email_list));

        // Block Email Domain
        $wpcf7_block_email_domain = sanitize_text_field($_POST['wpcf7_block_email_domain']);
        update_post_meta($post_id, "_wpcf7_block_email_domain", trim($wpcf7_block_email_domain));
        
        // Block Top Domain
        $wpcf7_block_top_domain = sanitize_text_field($_POST['wpcf7_block_top_domain']);
        update_post_meta($post_id, "_wpcf7_block_top_domain", trim($wpcf7_block_top_domain));
        
        // Protected Fields
        $wpcf7_protected_fields = sanitize_text_field($_POST['wpcf7_protected_fields']);
        update_post_meta($post_id, "_wpcf7_protected_fields", trim($wpcf7_protected_fields));

        // Block Words
        $wpcf7_block_words = sanitize_text_field($_POST['wpcf7_block_words']);
        update_post_meta($post_id, "_wpcf7_block_words", trim($wpcf7_block_words));
        
        // Short links
        $wpcf7_block_shortlinks = sanitize_text_field($_POST['wpcf7_block_shortlinks']);
        update_post_meta($post_id, "_wpcf7_block_shortlinks", trim($wpcf7_block_shortlinks));

        // Custom error message
        $wpcf7_block_email_error_msg = sanitize_text_field($_POST['wpcf7_block_email_error_msg']);
        update_post_meta($post_id, "_wpcf7_block_email_error_msg", trim($wpcf7_block_email_error_msg));
        
        // Enable / Disable logging
        $wpcf7_block_logging = sanitize_text_field($_POST['wpcf7_block_logging']);
        update_post_meta($post_id, "_wpcf7_block_logging", trim($wpcf7_block_logging));

        // Log filename
        $wpcf7_block_log_filename = sanitize_text_field( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $_POST['wpcf7_block_log_filename'] ) );
        // Ensure filename is not empty and ends with .log
        if (!empty($wpcf7_block_log_filename)) {
            // Ensure filename ends with .log
            if (pathinfo($wpcf7_block_log_filename, PATHINFO_EXTENSION) !== 'log') {
                $wpcf7_block_log_filename .= '.log';
            }
        } else {
            $wpcf7_block_log_filename = "spcf_spam_block.log";
        }
        
        update_post_meta($post_id, "_wpcf7_block_log_filename", trim($wpcf7_block_log_filename));        

        //Erase Log
        $erase_log = sanitize_text_field($_POST['wpcf7_block_log_erase']);
        if ($erase_log == "Erase log"){
            if (trim($wpcf7_block_log_filename)==""){
                $wpcf7_block_log_filename = "spcf_spam_block.log";
            }
            $log_handle = fopen("../wp-content/".$wpcf7_block_log_filename, "w");
           
            if ($log_handle !== false) {
                ftruncate($log_handle, 0);
                fclose($log_handle);
            }
        }
    }
    
    /**
     * Register the stylesheets for the admin area.
     */
    public function spcf7_enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/spam-protect-for-contact-form7.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     */
    public function spcf7_enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/spam-protect-for-contact-form7.js', array('jquery'), $this->version, false);
    }
}
