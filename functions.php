<?php
/**
 * Functions
 *
 * @package      base-genesis-child
 * @since        1.0.0
 * @author       Matt Whiteley <matt@whiteleydesigns.com>
 * @copyright    Copyright (c) 2014, Matt Whiteley
 * @license      http://opensource.org/licenses/gpl-2.0.php GNU Public License
 *
 */

//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Child Theme Name' );
define( 'CHILD_THEME_URL', 'http://www.studiopress.com/' );
define( 'CHILD_THEME_VERSION', '1.0.0' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//Ensure jQuery loads
add_action('init', 'mw_load_scripts', 0);
function mw_load_scripts() {
	wp_enqueue_script('jquery');
}

// Enqueue custom styles (google fonts, font-awesome, etc...)
add_action( 'wp_enqueue_scripts', 'mw_enqueue_google_fonts' );
function mw_enqueue_google_fonts() {
     wp_enqueue_style( 'google-font-open-sans', '//fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,700,800,600', array(), CHILD_THEME_VERSION );
     //wp_enqueue_style( 'font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css', array(), CHILD_THEME_VERSION );
}

//Remove default Genesis page templates (they are no good and I will never use them and they confuse clients...)
function wd_remove_genesis_page_templates( $page_templates ) {
	unset( $page_templates['page_archive.php'] );
	unset( $page_templates['page_blog.php'] );
	return $page_templates;
}
add_filter( 'theme_page_templates', 'wd_remove_genesis_page_templates' );


//Remove default genesis sidebar
remove_action( 'genesis_sidebar', 'genesis_do_sidebar' );
unregister_sidebar( 'header-right' );
unregister_sidebar( 'sidebar' );
unregister_sidebar( 'sidebar-alt' );
unregister_sidebar( 'footer-1' );
unregister_sidebar( 'footer-2' );
unregister_sidebar( 'footer-3' );

//Edit the header layout
remove_action( 'genesis_header', 'genesis_do_header' );
add_action( 'genesis_header', 'genesis_do_new_header' );
function genesis_do_new_header() {
     get_template_part( 'inc/header' );
}

//Edit the footer layout
remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'genesis_do_new_footer' );
function genesis_do_new_footer() {
     get_template_part( 'inc/footer' );
}

//Don't use this unless we really need to remove all the wrapping <p>'s WP creates
//Stop wordpress from wrapping everything in paragraph tags
//remove_filter( 'the_content', 'wpautop' );
//remove_filter( 'the_excerpt', 'wpautop' );

// Adding custom Favicon
//add_filter( 'genesis_pre_load_favicon', 'custom_favicon' );
//function custom_favicon( $favicon_url ) {
//	return get_stylesheet_directory_uri().'/images/favicon.ico';
//}

//* Remove comment form allowed tags
add_filter( 'comment_form_defaults', 'bg_remove_comment_form_allowed_tags' );
function bg_remove_comment_form_allowed_tags( $defaults ) {
	$defaults['comment_notes_after'] = '';
	return $defaults;
}

//* Remove site layouts
genesis_unregister_layout( 'content-sidebar' );
genesis_unregister_layout( 'sidebar-content' );
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );
genesis_unregister_layout( 'sidebar-content-sidebar' );

// Add main site description on any page that doesn't have a customized description
add_action( 'wp_head', 'wd_description' );
function wd_description() {
    global $post;
    $home_description = genesis_get_seo_option( 'home_description' ) ? genesis_get_seo_option( 'home_description' ) : get_bloginfo( 'description' );
    $page_description = genesis_get_custom_field( '_genesis_description' );
    if( $page_description == '' ) {
         echo '<meta name="description" content="' . esc_attr( $home_description ) . '" />' . "\n";
    };
}
