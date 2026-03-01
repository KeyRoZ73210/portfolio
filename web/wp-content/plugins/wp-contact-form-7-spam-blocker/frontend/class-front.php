<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/frontend
 */

/**
 * The public-facing functionality of the plugin.
 * 
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/frontend
 * @author     New York Software Lab
 * @link       https://nysoftwarelab.com
 */
class Spam_Protect_for_Contact_Form7_Front {

    /**
     * The unique identifier of this plugin.
     */
    private $plugin_name;

    /**
     * The current version of the plugin.
     */
    private $version;

    /**
     * Last email entered in form.
     */
    private $last_email;

    /**
     * Constructor of the class.
     */
    public function __construct($plugin_name, $version) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->last_email = "";

        add_filter('wpcf7_validate_email', array($this, 'spcf_check_email'), 10, 2); // Email field
        add_filter('wpcf7_validate_email*', array($this, 'spcf_check_email'), 10, 2); // Required Email field
        
        add_filter('wpcf7_validate_text', array($this, 'spcf_check_text'), 10, 2); // Text field
        add_filter('wpcf7_validate_text*', array($this, 'spcf_check_text'), 10, 2); // Required Text field

        add_filter('wpcf7_validate_textarea', array($this, 'spcf_check_text'), 10, 2); // Textarea field
        add_filter('wpcf7_validate_textarea*', array($this, 'spcf_check_text'), 10, 2); // Required Textarea field
    }

    /*
     Internal Function
     Write into log file 
    */
    private function spcf_write_log($log, $post_id){
        $wpcf7_block_log_filename = trim(get_post_meta($post_id, "_wpcf7_block_log_filename", true));
        
        // Use default value if nothing is setted as filename
        if (trim($wpcf7_block_log_filename)==""){
            $wpcf7_block_log_filename = "spcf_spam_block.log";
        }
        
        $file = fopen("wp-content/".$wpcf7_block_log_filename, "a");
        fwrite($file, "\n" . date('Y-m-d H:i:s') . " :: " . $log); 
        fclose($file); 
    }
    
    /*
     Internal Function
     Check email and domain if is banned 
    */
    private function spcf_check_email_and_domain($email, $post_id) {
        $blocked_emails_list_str = str_replace(" ", "", get_post_meta($post_id, "_wpcf7_block_email_list", true));
        $blocked_emails_domain_str = str_replace(" ", "", get_post_meta($post_id, "_wpcf7_block_email_domain", true));
        $blocked_top_domain_str = str_replace(" ", "", get_post_meta($post_id, "_wpcf7_block_top_domain", true));
        $wpcf7_block_logging = get_post_meta($post_id, "_wpcf7_block_logging", true);
        
        $blocked_emails = explode(",", trim($blocked_emails_list_str));
        $blocked_domains = explode(",", trim($blocked_emails_domain_str));
        $blocked_top_domains = explode(",", trim($blocked_top_domain_str));

        $email_domain = strstr($email, '@');
        $email_top_domain = strrchr($email, '.');

        if (in_array($email_domain, $blocked_domains) || in_array($email, $blocked_emails) || in_array($email_top_domain, $blocked_top_domains)) {
            if ($wpcf7_block_logging=="yes"){ 
                $ip = $this->spcf7_get_user_addr();
                $this->spcf_write_log("\tVisitors IP Address: ".$ip."\nThe following [EMAIL/DOMAIN] is blocked by the plugin's rules : ".$email."\n", $post_id);
            }
            return false;
        } else {
            return true;
        }
    }
    
    /*
     Internal Function
     Check textfields for banned words
    */
    private function spcf_check_text_process($value, $blocked_words, $post_id) {
        $wpcf7_block_logging = get_post_meta($post_id, "_wpcf7_block_logging", true);

        // all lower case
        $value_to_lower = strtolower($value);

        //remove double blank spaces but preserve newlines
        $value_ready = preg_replace( '/\h+/u', ' ', $value_to_lower);

        foreach ($blocked_words as $bw){
           if (strlen(trim($bw))>0){
                if (strpos($value_ready, $bw)!==false){
                    if ($wpcf7_block_logging=="yes"){ 
                        $ip = $this->spcf7_get_user_addr();
                        $this->spcf_write_log("\tVisitors IP Address: ".$ip." <".$this->last_email.">\nThe following [TEXT] is blocked by the plugin's rules: \n".$value_ready."\n", $post_id);
                    }
                    return false;
                }
           }
       }
       return true;
    }
    
    /*
     Internal Function
     Check for shortlinks
    */
    private function spcf_check_shortlinks($text_value, $shortlink_domains){
        foreach ($shortlink_domains as $v){
            $pos = strpos($text_value, $v);
            if ($pos !== false) {
                return true;
            }
        }
        return false;
    }

    /*
     Internal Function
     Get IP Address
    */
    private function spcf7_get_user_addr() {
        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        
        return $ip;
    }

    /*
     HOOK - wpcf7_validate_text
    */
    public function spcf_check_text($result, $tag) {
        
        $name = $tag['name'];
        $basetype = $tag['basetype'];
        $post_id = sanitize_text_field($_POST['_wpcf7']);
        
        $wpcf7_block_email_error_msg = get_post_meta($post_id, "_wpcf7_block_email_error_msg", true);
        
        $blocked_words_str = strtolower(get_post_meta($post_id, "_wpcf7_block_words", true));
        $blocked_words = array_map('trim', explode(",", trim($blocked_words_str)));
        
        $protected_fields_str = get_post_meta($post_id, "_wpcf7_protected_fields", true);
        $protected_fields = array_map('trim', explode(",", trim($protected_fields_str)) );

        $blocked_shortlinks = get_post_meta($post_id, "_wpcf7_block_shortlinks", true);

        $wpcf7_block_logging = get_post_meta($post_id, "_wpcf7_block_logging", true);
        
        if ($basetype == 'text' || $basetype == 'textarea') {
            $value = $_POST[$name]; //Check un-sanitized_text, important to block html code and links posted from the form
            
            // Remove punctuation "." and "!" from text body
            $text_value = str_replace(".", "", $value);
            $text_value = str_replace("!", "", $value);

            // Check short links first
            if ($blocked_shortlinks == "yes"){
                /* bit.ly/,  rb.gy/, tinyurl.com/ */
                $shortlink_domains = array("bit.ly/",  "rb.gy/", "tinyurl.com/");
                $has_shortlinks = $this->spcf_check_shortlinks($text_value, $shortlink_domains);
                if ($has_shortlinks){
                    $result->invalidate($tag, $wpcf7_block_email_error_msg);
                }
            }

            // check protected fields for empty submitions
            if(in_array($tag['name'], $protected_fields)){
                if (strlen(trim(sanitize_text_field( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $value))))==0){
                    $result->invalidate($tag, $wpcf7_block_email_error_msg);
                }
            }

            // Check text for blocked words 
            if (!$this->spcf_check_text_process($text_value, $blocked_words, $post_id)) {
                $result->invalidate($tag, $wpcf7_block_email_error_msg);
            }
        }
        
        return $result;
    }
    
    /*
     HOOK - wpcf7_validate_email
    */
    public function spcf_check_email($result, $tag) {
        $name = $tag['name'];
        $basetype = $tag['basetype'];
        $post_id = sanitize_text_field($_POST['_wpcf7']);
               
        $wpcf7_block_email_error_msg = get_post_meta($post_id, "_wpcf7_block_email_error_msg", true);

        if ($basetype == 'email') { // Only apply to fields with the form field name of "your-email"
            $value = $_POST[$name]; //Check un-sanitized_text, important to block html code and links posted from the form  
            $this->last_email = $value;
            if (!$this->spcf_check_email_and_domain($value, $post_id)) {
                $result->invalidate($tag, $wpcf7_block_email_error_msg);
            }
        }
        return $result;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     */
    public function spcf7_enqueue_styles() {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/spam-protect-for-contact-form7.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     */
    public function spcf7_enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/spam-protect-for-contact-form7.js', array('jquery'), $this->version, false);
    }

}
