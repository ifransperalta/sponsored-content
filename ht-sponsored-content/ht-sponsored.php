<?php
/**
* Plugin Name: Sponsored Contents 
* Plugin URI: 
* Description: sponsored content taxonomy settings
* Version: 1.0
* Author: 
* Author URI: 
**/

register_activation_hook( __FILE__, 'htsponsoredcontent');
include plugin_dir_path( __FILE__ ) . 'SponsoredContent.php';
include plugin_dir_path( __FILE__ ) . 'SponsoredContentHTML.php';

session_start();
$_SESSION["arrKeysFront"] = array();
$_SESSION["arrKeysList"] = array();

function htsponsoredcontent() {   
    register_htsponsored();
    flush_rewrite_rules();
}
 
function register_htsponsored() {
     $labels = array(
        'name' => _x( 'Sponsored Contents', 'htsc' ),
        'singular_name' => _x( 'Sponsored Contents', 'htsc' ),
        'edit_item' => _x( 'Edit Sponsored Contents', 'htsc' ),
        'view_item' => _x( 'View Sponsored Contents', 'htsc' ),
        'search_items' => _x( 'Search Sponsored Contents', 'htsc' ),
        'not_found' => _x( 'No Sponsored Contents found', 'htsc' ),
        'not_found_in_trash' => _x( 'No Sponsored Contents found in Trash', 'htsc' ),
        'parent_item_colon' => _x( 'Parent Sponsored Contents:', 'htsc' ),
        'menu_name' => _x( 'Sponsored Contents', 'htsc' ),
    );
 
    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'description' => 'Sponsored Content',
        'supports' => array( 'title', 'editor', 'thumbnail', ),
        'taxonomies' => array( 'htsc' ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 100,
        'menu_icon' => 'dashicons-media-text',
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );  
    register_post_type('sc', $args );
}
 
add_action( 'init', 'register_htsponsored' );

function sponsored_content_taxonomy() {
    register_taxonomy(
        'sponsoredContent',
        'sc',
        array(
            'hierarchical' => true,
            'label' => 'Category',
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'sponsoredContent',
                'with_front' => false
            )
        )
    );
}
add_action( 'init', 'sponsored_content_taxonomy');

// Show posts of 'post', 'page' and 'movie' post types on home page
function add_sponsored_content( $query ) {
    if ( is_home() && $query->is_main_query() )
    $query->set( 'post_type', array( 'post', 'htsc' ) );
    return $query;
}
add_action( 'pre_get_posts', 'add_sponsored_content' );
 
function sc_shortcode_sm( $atts ){        
   $sc = new SponsoredContent();
   echo $sc->getFrontPageSc();
}
add_shortcode('ht-sponsoredcontent', 'sc_shortcode_sm');

function singleStorySidebar(){
    $arrEntry = array();
    $sc = new SponsoredContent();
    $arrEntry[] = $sc->getSidebarSC();
    return  $arrEntry; 
}
?>