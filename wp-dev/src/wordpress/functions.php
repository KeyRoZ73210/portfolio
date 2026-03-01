<?php
/**
 * wp-dev functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package wp-dev
 */

define('VERSION', '1.0');
define("ROOT", get_stylesheet_directory_uri());
define("IMG", get_stylesheet_directory_uri().'/assets/img');
// define('DEFAULT_EMAIL', "dev@wp-dev.com");
// show_admin_bar(false);

/* ========================================================================= *\
**  === SECURE API REST                                                      *| 
\* ========================================================================= */

add_filter( 'rest_authentication_errors', function( $result ) {
	// If a previous authentication check was applied,
	// pass that result along without modification.
	if ( true === $result || is_wp_error( $result ) ) {
			return $result;
	}

	// No authentication has been performed yet.
	// Return an error if user is not logged in.
	if ( ! is_user_logged_in() ) {
			return new WP_Error(
					'rest_not_logged_in',
					__( 'You are not currently logged in.' ),
					array( 'status' => 401 )
			);
	}

	// Our custom authentication check should have no effect
	// on logged-in requests
	return $result;
});

/* ========================================================================= *\
**  === AFTER SETUP THEME.                                                   *| 
\* ========================================================================= */
 
add_action( 'after_setup_theme', 'theme_setup' );
function theme_setup() {
	// Rempli les balises title en fonction du titre de la page
	add_theme_support( 'title-tag' );

	// Ajoute les images à la une des pages et des articles
	add_theme_support( 'post-thumbnails' );


	remove_action('wp_head', 'wp_generator');
}

/* ========================================================================= *\
**  === ENQUEUE SCRIPTS AND STYLES                                           *| 
\* ========================================================================= */
function theme_scripts_styles() {

  wp_enqueue_style('style', ROOT . '/style.css', [], VERSION);

  $env = function_exists('wp_get_environment_type')
    ? wp_get_environment_type()
    : 'production';

  $is_dev = in_array($env, ['local', 'development'], true);

  $css_file = $is_dev ? '/assets/css/main.css' : '/assets/css/main.min.css';
  $js_file  = $is_dev ? '/assets/js/main.js'  : '/assets/js/main.min.js';

  $css_path = get_template_directory() . $css_file;
  $js_path  = get_template_directory() . $js_file;

  $css_uri  = get_template_directory_uri() . $css_file;
  $js_uri   = get_template_directory_uri() . $js_file;


  $css_version = file_exists($css_path) ? filemtime($css_path) : VERSION;
  $js_version  = file_exists($js_path)  ? filemtime($js_path)  : VERSION;

  wp_enqueue_style('main', $css_uri, [], $css_version);

  wp_enqueue_script('main', $js_uri, [], $js_version, true);


  wp_script_add_data('main', 'type', 'module');
}

add_action('wp_enqueue_scripts', 'theme_scripts_styles');

add_filter('script_loader_tag', function ($tag, $handle, $src) {
  if ($handle === 'main') {
    return sprintf(
      '<script type="module" src="%s" id="%s"></script>' . "\n",
      esc_url($src),
      esc_attr($handle . '-js')
    );
  }
  return $tag;
}, 10, 3);

// Defer script
// incompatibility : underscore.js
// add_filter( 'script_loader_tag', 'defer_scripts', 10, 3 );
// function defer_scripts( $tag, $handle, $src ) {
// 	$defer_scripts = array( 
// 		'maps',
// 		'script1',
// 		'script2'
// 	);
// 	if ( in_array( $handle, $defer_scripts ) ) {
// 		return '<script defer type="text/javascript" src="' . $src . '"></script>' . "\n";
// 	}
// 	return $tag;
// } 


function is_local() {
	$localAdresses = array(
		'127.0.0.1',
		'::1'
	);
	if(in_array($_SERVER['REMOTE_ADDR'], $localAdresses)){
		return true;
	}
	return false;
}

/* ========================================================================= *\
**  === MENU                                                                 *| 
\* ========================================================================= */

function wpb_custom_new_menu() {
  // register_nav_menu('menu-page-selector',__( 'Menu page selector' ));
  register_nav_menu('header',__( 'Header' ));
  register_nav_menu('footer',__( 'Footer' ));
}
add_action( 'init', 'wpb_custom_new_menu' );

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Theme Options',
		'menu_title'	=> 'Theme Options',
		'menu_slug' 	=> 'theme-options',
		'capability'	=> 'edit_posts',
		'icon_url' => 'dashicons-admin-generic',
		'position' => 2
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Settings',
		'menu_title'	=> 'Settings',
		'menu_slug' 	=> 'theme-options-settings',
		'parent_slug'	=> 'theme-options',
	));
}

/**
 * Ajoute en variable global les variables options du thème.
 * 
 * @param String/Null		Nom du champ (ACF) appartenant au theme-option
 * @return String/Array 	Données du champ (ou tous les champs de theme-option si @param = null)
 */ 
function getThemeOption($targetedField = null){
	if($targetedField == null) return get_fields('option');
	return get_field($targetedField, 'option');
}


/* ========================================================================= *\
**  === THUMBNAILS                                                           *| 
\* ========================================================================= */

add_filter( 'big_image_size_threshold', '__return_false' );

function wp_img($id, $size = 'full', $attr = '') {
  return wp_get_attachment_image( $id, $size, false, $attr );
}

// add_action( 'after_setup_theme', 'wpdocs_theme_setup' );
// function wpdocs_theme_setup() {
// 	add_image_size( 'full-width', 1500 );
// 	add_image_size( 'rounded', 300, 300, true );
// 	add_image_size( 'slider-thumbnail', 330, 165, true );
// 	add_image_size( 'animated-wheel', 500, 500, true );
// 	add_image_size( 'semi-full', 720 );
// 	add_image_size( 'program-slider', 1110, 650, true );
// 	add_image_size( 'program-thumbnail', 365, 460, true );
// 	add_image_size( 'program-large', 670, 450, true );
// 	add_image_size( 'program-square', 340, 332, true );
// 	add_image_size( 'blog-thumbnail', 350, 175, true );
// 	add_image_size( 'blog-featured', 730, 865, true );
// }


/* ========================================================================= *\
**  === ACF MAPS                                                             *| 
\* ========================================================================= */

// Acf google map
// function my_acf_init() {
// 	acf_update_setting('google_api_key', 'xxxxxxxxxxxxxxxxxxxxxxx');
// }
// add_action('acf/init', 'my_acf_init');


/* ========================================================================= *\
**  === WPML - ICL LANGUAGE                                                  *| 
\* ========================================================================= */

// if(defined('ICL_LANGUAGE_CODE')){
// 	define('LANG', ICL_LANGUAGE_CODE);
// }
// else {
// 	define('LANG', 'fr');
// }

// // CUSTOM FUNCTION THAT DISPLAY THE URL OF THE PAGE ID TRANSLATED
// function href($id){
// 	if(!defined("ICL_LANGUAGE_CODE")){
// 		return get_permalink($id); // Get the URL of a translated page (current language displayed)
// 	}
// 	else {
// 		return get_permalink( icl_object_id($id, 'page', true) ); // Get the URL of a translated page (current language displayed)
// 	}
// }
// // Get the link of the current translated page
// function switchLanguageTo($lang) {
//     return apply_filters( 'wpml_permalink', get_the_permalink() , $lang );
// }


/* ========================================================================= *\
**  === SMTP                                                                 *| 
\* ========================================================================= */
function custom_wp_mail_smtp($phpmailer) {
	// Debug mode = 2
	$phpmailer->SMTPDebug = 0;

	// Forcer l'utilisation de SMTP
  $phpmailer->isSMTP();

  // Adresse du serveur SMTP
  $phpmailer->Host = '----.net';

  // Type de chiffrement.
  // $phpmailer->SMTPSecure = 'ssl';

  // Port du serveur SMTP
  $phpmailer->Port = 587;

	$phpmailer->SMTPAuth = true;

  // Nom d'utilisateur pour le serveur SMTP
  $phpmailer->Username = '-----';

  // Mot de passe pour le serveur SMTP
  $phpmailer->Password = '----';

  

}
// add_action('phpmailer_init', 'custom_wp_mail_smtp');

/* ========================================================================= *\
**  === FORMS CONTACT 7                                                      *| 
\* ========================================================================= */

// Ajoute la possibilité de mettre des shortcode dans le corps de CF7 
add_filter( 'wpcf7_form_elements', 'do_shortcode' );

add_filter('wpcf7_form_elements', function($content) {
	$content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);
	return $content;
});

