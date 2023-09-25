<?php

// required lib files.
include('custom-functions.php');
include('lib/tdp-functions.php');

/*
 * Register sidebars
 */
function register_ai_child_starter_theme_sidebars() {
	
	/*register_sidebar(array( 
	   'name' => 'My Custom Sidebar',
	   'id'=>'my-custom-sidebar',
	   'before_widget' => '',
	   'after_widget' => '',
	   'before_title' => '',
	   'after_title' => ''
    ));*/
	

	register_sidebar(array( 
	   'name' => 'Main Logo',
	   'id'=>'wp-main-logo',
	   'before_widget' => '',
	   'after_widget' => '',
	   'before_title' => '',
	   'after_title' => ''
    ));

	register_sidebar(array( 
	   'name' => 'Mini Logos',
	   'id'=>'wp-mini-logo',
	   'before_widget' => '',
	   'after_widget' => '',
	   'before_title' => '',
	   'after_title' => ''
    ));

	register_sidebar(array( 
	   'name' => 'Legends',
	   'id'=>'wp-legends',
	   'before_widget' => '',
	   'after_widget' => '',
	   'before_title' => '',
	   'after_title' => ''
    ));

	register_sidebar(array( 
	   'name' => 'Homepage Chart Data',
	   'id'=>'wp-homepage-chart-data',
	   'before_widget' => '',
	   'after_widget' => '',
	   'before_title' => '',
	   'after_title' => ''
    ));


}

add_action( 'widgets_init', 'register_ai_child_starter_theme_sidebars', 11 );

/*
 * Enqueue theme styles and scripts
 */
function ai_starter_theme_enqueue_child_assets() {

	wp_enqueue_style('google-font', 'https://fonts.googleapis.com/css?family=Open+Sans|Raleway:300,400,700|Arimo:400,400i,700,700i,800|Montserrat:600');

	wp_enqueue_style("agentimage-font", "http://cdn.thedesignpeople.net/agentimage-font/fonts/agentimage.font.icons.css");


	// enqueue in directory.php template only
	if ( basename( get_page_template() ) == 'directory.php') {
		wp_enqueue_style('table-style', get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css');
	}

	wp_enqueue_style('jschart-style', get_stylesheet_directory_uri() . '/css/jquery.orgchart.css');

	wp_enqueue_style('directory_style', get_stylesheet_directory_uri() . '/css/directory.css');

	/* Enqueue my scripts */
	wp_enqueue_script('jschart-script', get_stylesheet_directory_uri() . '/js/jquery.orgchart-a99.min.js');

	wp_enqueue_script('jquery-chained-height', get_stylesheet_directory_uri() . '/js/jquery.chain-height.js');
	wp_enqueue_script('jquery-data-tables', get_stylesheet_directory_uri() . '/js/jquery.dataTables.min.js');
	wp_enqueue_script('aios-starter-theme-child-script', get_stylesheet_directory_uri(). '/js/scripts.js');


}

add_action( 'wp_enqueue_scripts', 'ai_starter_theme_enqueue_child_assets', 11 );
add_action( 'wp_enqueue_scripts', 'ai_starter_theme_remove_media_queries_from_child_stylesheet', 13 );


function load_custom_wp_admin_style() {
    wp_enqueue_style( 'wp-admin-custom-styles', get_stylesheet_directory_uri(). '/lib/admin-styles.css' );
    wp_enqueue_script( 'wp-admin-custom-script', get_stylesheet_directory_uri(). '/lib/admin-scripts.js' );
}
//add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

/*
 * Add custom data attributes to menu items
 */
function ai_starter_theme_add_menu_link_attributes( $atts, $item, $args ) {
	$atts['data-title'] = $item->title;
	return $atts;
}

add_filter( 'nav_menu_link_attributes', 'ai_starter_theme_add_menu_link_attributes', 10, 3 );

/*
 * Add image sizes
 */
//add_image_size('cyclone-slide', 1024, 768, true);
 
/*
 * Define content width
 */
if ( ! isset( $content_width ) ) {
	$content_width = 960;
}

function custom_loginlogo() {
echo '<style type="text/css">
h1 a {background-image: url('.get_bloginfo('stylesheet_directory').'/images/august99.png) !important;width: 230px !important;
    background-size: 100% !important; }
</style>';
}
add_action('login_head', 'custom_loginlogo');