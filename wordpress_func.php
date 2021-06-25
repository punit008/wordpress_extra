<?php

require __DIR__ . '/vendor/autoload.php';

use PostTypes\PostType;
use PostTypes\Taxonomy;

(new Taxonomy('issuu_folder'))->options(['hierarchical' => false,])->register();

(new PostType('issuu-magazines'))->taxonomy('issuu_folder')->register();


/**
 * beacon functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package beacon
 */

if ( ! function_exists( 'beacon_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function beacon_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on beacon, use a find and replace
		 * to change 'beacon' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'beacon', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'beacon' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'beacon_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'beacon_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function beacon_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'beacon_content_width', 640 );
}
add_action( 'after_setup_theme', 'beacon_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function beacon_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'beacon' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'beacon' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'beacon_widgets_init' );
/**
 * Enqueue scripts and styles.
 */
function beacon_scripts() {
	$currDate = time();
    //wp_enqueue_style( 'beacon-fonts','https://fonts.googleapis.com/css?family=Roboto:300,400,500,700');
    wp_enqueue_style( 'beacon-jquery-ui-css', get_template_directory_uri() . '/css/jquery-ui.min.css');
    wp_enqueue_style( 'beacon-timepicker-css', get_template_directory_uri() . '/css/jquery.timepicker.min.css');
    wp_enqueue_style( 'beacon-carousel-css', get_template_directory_uri() . '/css/owl.carousel.css');
    wp_enqueue_style( 'beacon-bootstrap-css', get_template_directory_uri() . '/css/bootstrap.min.css');
    wp_enqueue_style( 'beacon-font-awesome', get_template_directory_uri() . '/css/font-awesome.min.css');
    wp_enqueue_style( 'beacon-fancybox-css', get_template_directory_uri() . '/css/jquery.fancybox.css');
	wp_enqueue_style( 'beacon-style', get_stylesheet_uri() );
	wp_enqueue_style( 'beacon-responsive-css', get_template_directory_uri() . '/css/responsive.css');
    
    
    wp_enqueue_script('jquery');
    
    wp_enqueue_script( 'beacon-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), $currDate, true ); 
    wp_enqueue_script( 'beacon-bootstrap-min', get_template_directory_uri() . '/js/bootstrap.min.js', array(), $currDate, true ); 
   // wp_enqueue_script( 'add-this', "//platform-api.sharethis.com/js/sharethis.js#property=5a0e840bcef24500111c15c5&product=sticky-share-buttons", array(), $currDate, true ); 
    
    wp_enqueue_script( 'beacon-jquery-ui', get_template_directory_uri() . '/js/jquery-ui.min.js', array(), $currDate, true ); 
    wp_enqueue_script( 'beacon-timepicker', get_template_directory_uri() . '/js/jquery.timepicker.min.js', array(), $currDate, true ); 
    wp_enqueue_script( 'beacon-owl-carousel', get_template_directory_uri() . '/js/owl.carousel.min.js', array(), $currDate, true ); 
    wp_enqueue_script( 'beacon-jquery-fancybox-pack', get_template_directory_uri() . '/js/jquery.fancybox.pack.js', array(), $currDate, true ); 
    
	wp_enqueue_script( 'beacon-custom', get_template_directory_uri() . '/js/custom.js', array(), '20151215', true );
 
		wp_localize_script( 'beacon-custom', 'beacon_admin_URL_Name', beacon_admin_URL());
		
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'beacon_scripts' );

/**
 * Admin Custom Enqueue scripts and styles.
 */
function admin_custom_scripts() {
	wp_enqueue_script('admin-custom-js', get_theme_file_uri( '/js/admin-custom.js' ), array( 'jquery' ), '1.0', true );
	wp_localize_script('admin-custom-js', 'beacon_admin_URL_Name', beacon_admin_URL());
	
	wp_enqueue_script('jquery-ui-datepicker');
	if(!empty($_REQUEST['page'])) {
		if($_REQUEST['page'] == 'advertisement') {
	    	wp_enqueue_style('jquery-ui', get_theme_file_uri( '/css/jquery-ui.css' ));
		}
	}
}
add_action( 'admin_enqueue_scripts', 'admin_custom_scripts' );

function beacon_admin_URL() { 	
	$admin_URL = admin_url( 'admin-ajax.php' ); // Your File Path
     return array(
        'admin_URL' =>  $admin_URL,
     );
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}



remove_action('wp_head', 'wlwmanifest_link');

remove_action('wp_head', 'feed_links_extra', 3);

remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);

remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );

remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

function wpt_remove_version() {  
	return '';  
}  
add_filter('the_generator', 'wpt_remove_version');

function remove_cssjs_ver( $src ) {
    if( strpos( $src, '?ver=' ) )
        $src = remove_query_arg( 'ver', $src );
    return $src;
}
if(!is_admin()){
    add_filter( 'style_loader_src', 'remove_cssjs_ver', 10, 2 );
    add_filter( 'script_loader_src', 'remove_cssjs_ver', 10, 2 );
}

// Disable support for comments and trackbacks in post types
function beacon_disable_comments_post_types_support() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if(post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}
add_action('admin_init', 'beacon_disable_comments_post_types_support');

// Close comments on the front-end
function beacon_disable_comments_status() {
    return false;
}
add_filter('comments_open', 'beacon_disable_comments_status', 20, 2);
add_filter('pings_open', 'beacon_disable_comments_status', 20, 2);


// Hide existing comments
function beacon_disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
}
add_filter('comments_array', 'beacon_disable_comments_hide_existing_comments', 10, 2);


// Remove comments page in menu
function beacon_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'beacon_disable_comments_admin_menu');

// Redirect any user trying to access comments page
function beacon_disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url()); exit;
    }
}
add_action('admin_init', 'beacon_disable_comments_admin_menu_redirect');

// Remove comments metabox from dashboard
function beacon_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('admin_init', 'beacon_disable_comments_dashboard');

// Remove comments links from admin bar
function beacon_disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}
add_action('init', 'beacon_disable_comments_admin_bar');


// Includes files
require get_template_directory() . '/includes/breadcrumbs.php';
require get_template_directory() . '/includes/tag-archive-fun.php';
require get_template_directory() . '/includes/type_silverpage.php';
require get_template_directory() . '/includes/type_distributions.php';
require get_template_directory() . '/includes/type_advertise.php';
require get_template_directory() . '/includes/type_comics.php';
require get_template_directory() . '/includes/type_events.php';
require get_template_directory() . '/includes/admin-silverpage-tracking.php';

// Custom Image Sizes

add_image_size( 'cat_banner_img', 1221 , 123 ,array('center','center'));
add_image_size( 'banner_slider_img', 802 , 408 ,array('center','center'));
add_image_size( 'silver_slider_img', 378 , 408 ,array('center','center'));
add_image_size( 'silver_footer_img', 378 , 378 ,array('center','center'));
add_image_size( 'home_art_img', 378 , 209 ,array('center','center'));
add_image_size( 'ads_block_img', 250 , 250 ,array('center','center'));
add_image_size( 'cat_list_img', 218 , 200 ,array('center','center'));
add_image_size( 'silver_page_image', 588 , 426 ,array('center','center'));
add_image_size( 'silver_gallery_image', 168 , 157 ,array('center','center'));
add_image_size( 'silver_feature_image', 312 , 292 ,array('center','center'));
add_image_size( 'header_ads_image', 728 , 90 ,array('center','center'));
add_image_size( 'beacon_online_image', 233 , 236 ,array('left','top'));
add_image_size( 'beacon_flipbook_big', 733 , 987 ,array('center','center'));
add_image_size( 'beacon_flipbook_small', 170 , 227 ,array('center','center'));


/**
 *  Add Acf options page in admin
 */

if( function_exists('acf_add_options_page') ) {
	$option_page = acf_add_options_page(array(
		'page_title' 	=> 'Theme Settings',
		'menu_title' 	=> 'Theme Settings',
		'menu_slug' 	=> 'theme-settings',
		'capability' 	=> 'edit_posts',
		'redirect' 	=> false
	));
}

// Show only publish page
add_filter('acf/fields/relationship/query/name=footer_pages_button', 'relationship_options_filter', 10, 3);
function relationship_options_filter($options, $field, $the_post) {
	
	$options['post_status'] = array('publish');
	
	return $options;
}

// custom excerpt length
function beacon_excerpt_length( $length ) {
   return 63;
}
add_filter( 'excerpt_length', 'beacon_excerpt_length', 999 );

// add more link to excerpt
function beacon_excerpt_more($more) {
   global $post;
   return "...<a href=".get_permalink($post->ID).">READ MORE</a>";
}
add_filter('excerpt_more', 'beacon_excerpt_more');

// Add Category color class in Body
add_filter( 'body_class', 'beacon_custom_class' );
function beacon_custom_class( $classes ) {
	
	$category_id = get_queried_object_id();
    if ( is_category($category_id) ) {
		$category_color = get_field('color_option','category_'.$category_id);
		$category_color = (!empty($category_color)) ? $category_color : 'gray' ;
        $classes[] = $category_color;
    }	
	if(is_singular('post')){
		$cat_id = get_the_category($category_id);
		$category_color = get_field('color_option','category_'.$cat_id[0]->term_id);
		$category_color = (!empty($category_color)) ? $category_color : 'gray' ;
		$classes[] = $category_color;
	}
    return $classes;
}


// LimitText 
function beacon_limitText_content($string , $len=0){
	if (strlen(strip_tags($string)) <= $len || $len == 0) {
       return strip_tags($string);
    }
	$newstr = substr(strip_tags($string), 0, $len);
	if ( substr($newstr,-1,1) != ' ' ) 
        $newstr = substr($newstr, 0, strrpos($newstr, " "));
    return $newstr.'...';
}

// Custom Pagination	
function beacon_pagination($query=NULL , $numpages=NULL) {
	global $wp_query;
	if(!empty($query)){
		$wp_query = $query;
	}
	$big = 99999999;
	if ($numpages) {
		// $total_posts = $qry->found_posts;
		$pages = $numpages;
	}
	else{
		// $total_posts = $wp_query->found_posts;
		$pages = $wp_query->max_num_pages;	
	}
	$page_format = paginate_links( array(
		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $pages,
		'prev_text'    => 'Previous',
		'next_text'    => 'Next',
		'type'  => 'array'	
		) 
	);

	if( is_array($page_format) ) {
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		echo '<div class="pagination-block"><ul class="pagination">';
		if($paged == 1){
			echo '<li class="disabled page-item"><a>Previous</a><li>';
		}
		foreach ( $page_format as $page ) {
			    
				echo "<li class='page-item'>$page</li>";
				
		}
		if($paged == $pages){
			echo '<li class="disabled page-item"><a>Next</a><li>';
		}
	    echo '</ul></div>';
	}
}

// Upload Featured Image Instruction
add_filter( 'admin_post_thumbnail_html', 'beacon_featured_image_instruction');
function beacon_featured_image_instruction($content) {
 global $post;
 $post_type = get_post_type();
	if($post_type == 'post'){
		$content .= '<p>Please upload 802 X 408 image size only.</p>';
	}
	if($post_type == 'silverpage'){
		$content .= '<p>Please upload 312 X 292 image size only.</p>';
	}
	if($post_type == 'page'){
		$content .= '<p>Please upload 1221 X 123 image size only for banner.</p>';
	}
    return $content;
}

// Set google api key in ACF Field
function my_acf_google_map_api( $api ){
	$api['key'] = 'AIzaSyAQRFhih2cp0o-EW9s4FfWBr1Ld4uTnyh0';
	return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

//add_filter('pre_get_posts', 'beacon_query_post_type');
function beacon_query_post_type($query) {
	if ( $query->is_main_query() && $query->is_tax() ) {
        $post_type = get_query_var('post_type');
        if($post_type) {
            $post_type = $post_type;
        } else {
            $post_type = array('post','silverpage'); // replace CPT to your custom post type
        }
        $query->set('post_type',$post_type);

    }
	return $query;
}

// Insert longitude and latitude entry in custom tabel

//add_action('pre_post_update', 'save_silver_page_location');
add_action('save_post', 'save_silver_page_location');
function save_silver_page_location($post_id){
	global $wpdb;
	$post_type = get_post_type($post_id);
	
	if($post_type == 'silverpage') {
		
		$delete_query = "DELETE FROM `wp_location_latlong` WHERE `post_id` = ".$post_id;
		$result = $wpdb->query($delete_query);	
		// Enter Map lat/lang
		$post_location_data = get_field('google_map_location',$post_id);
		if(!empty($post_location_data)){
			
		$location_lat = $post_location_data['lat'];
		$location_lng = $post_location_data['lng'];
		$tabel_name = $wpdb->prefix.'location_latlong';
		
			$query = "INSERT INTO $tabel_name (post_id,location_latitude,location_longitude)
						VALUES ('$post_id', '$location_lat', '$location_lng')";						
		
			$result = $wpdb->query($query);			
		}	
		
		// Enter Zip lat/lang
		
		$location_zipcode = get_field('multiple_zipcodes',$post_id);

		if(!empty($location_zipcode)){	
			foreach($location_zipcode as $zipcodes){
				if(!empty($zipcodes)){
					$zipcode_single = $zipcodes['zipcode'];
					
					$geoData = array();
					$tabel_name = $wpdb->prefix.'location_latlong';
					$geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAQRFhih2cp0o-EW9s4FfWBr1Ld4uTnyh0&address='.$zipcode_single.',US&sensor=false');
					$geo = json_decode($geo, true);
					if ($geo['status'] = 'OK') {
						$zip_location_lat = $geo['results'][0]['geometry']['location']['lat'];
						$zip_location_lng = $geo['results'][0]['geometry']['location']['lng'];
						
						echo "Zipcode = ".$zipcode_single." Lat = ".$zip_location_lat." Lng = ".$zip_location_lng."<br/>";
						
						$zip_query = "INSERT INTO $tabel_name (post_id,location_latitude,location_longitude)
						VALUES ('$post_id', '$zip_location_lat', '$zip_location_lng')";
						
						$result = $wpdb->query($zip_query);	
						
					}	
				}
			} 
		} 
	} elseif($post_type == 'distribution') {
		$tabel_name = $wpdb->prefix.'location_latlong';
		$delete_query = "DELETE FROM $tabel_name WHERE `post_id` = ".$post_id;
		$result = $wpdb->query($delete_query);	
		// Enter Map lat/lang
		$zip_code = get_field('zip_code',$post_id);
		$post_location_data = getLatLng($zip_code);
		if(!empty($post_location_data)){
			
		$location_lat = $post_location_data['lat'];
		$location_lng = $post_location_data['lng'];
		$tabel_name = $wpdb->prefix.'location_latlong';
		
			$query = "INSERT INTO $tabel_name (post_id,location_latitude,location_longitude)
						VALUES ('$post_id', '$location_lat', '$location_lng')";						
		
			$result = $wpdb->query($query);			
		}
	}
}

// Get post list and distance 
function get_location_distance($latitude ,$longitude,$distance=0){

	// return 0;
	// return array( "post_id" => '0', "distance" => '0' ) ;  

	global $wpdb,$post;
	$category_id = get_queried_object_id();
	$category_near = isset($_REQUEST['category-near']) ? $_REQUEST['category-near'] : '' ;
	$category_detail = isset ($_REQUEST['category-detail']) ? $_REQUEST['category-detail'] : '';
	$cat_query = '';
	$child_id = '';
	if(!empty($category_near)){
		$cat_query = " AND {$wpdb->prefix}term_relationships.term_taxonomy_id = ".$category_near;
		$child_id = ','.$category_near;
	}
	if(!empty($category_detail)){
		$cat_query = " AND {$wpdb->prefix}term_relationships.term_taxonomy_id = ".$category_detail;
		$child_id = ','.$category_detail;
	}

	$featured_query = "SELECT DISTINCT {$wpdb->prefix}posts.ID as post_id, Min( 3959 * ACOS( COS( RADIANS( $latitude ) ) * COS( RADIANS( ll.location_latitude ) ) 
		* COS( RADIANS( ll.location_longitude ) - RADIANS( $longitude ) ) + SIN( RADIANS( $latitude ) ) * SIN( RADIANS( ll.location_latitude ) ) ) ) AS distance FROM {$wpdb->prefix}posts 
		LEFT JOIN {$wpdb->prefix}location_latlong ll ON {$wpdb->prefix}posts.ID = ll.post_id 
		LEFT JOIN {$wpdb->prefix}postmeta ON {$wpdb->prefix}posts.ID = {$wpdb->prefix}postmeta.post_id
		LEFT JOIN {$wpdb->prefix}postmeta pp ON {$wpdb->prefix}posts.ID = pp.post_id
		LEFT JOIN {$wpdb->prefix}term_relationships ON ({$wpdb->prefix}posts.ID = {$wpdb->prefix}term_relationships.object_id) 
					   INNER JOIN {$wpdb->prefix}term_taxonomy ON ({$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id $cat_query)
					   WHERE 1=1 AND ( ( {$wpdb->prefix}postmeta.meta_key = 'is_featured' AND {$wpdb->prefix}postmeta.meta_value = '1' ) AND ( pp.meta_key = 'featured_expiration_date' AND pp.meta_value >= DATE_FORMAT(NOW(),'%Y%m%d')) AND {$wpdb->prefix}posts.post_type = 'silverpage' AND ({$wpdb->prefix}posts.post_status = 'publish' AND {$wpdb->prefix}term_taxonomy.term_id IN ($category_id $child_id)) ) GROUP BY {$wpdb->prefix}posts.ID";
	if(!empty($distance) && $distance != 0){
		$featured_query .= " HAVING distance < ".$distance;	
	}
	$featured_query .= " ORDER BY distance ASC";
	/*if($_SERVER['REMOTE_ADDR'] == '103.251.217.63'){
		echo $featured_query;
		exit;
	}*/
	$featured_result = $wpdb->get_results($featured_query,ARRAY_A);	
	
	$promotion_ids = wp_list_pluck( $featured_result, 'post_id' );

	$category_id = get_queried_object_id();
	$category_near = isset($_REQUEST['category-near']) ? $_REQUEST['category-near'] : '' ;
	$category_detail = isset ($_REQUEST['category-detail']) ? $_REQUEST['category-detail'] : '';
	$cat_query = '';
	$child_id = '';
	
	
	
    if ( is_category($category_id) ) {
		$category_id = $category_id;
    }	
	if(is_singular('silverpage')){
		$post_id = $post->ID;
		$category = wp_get_post_terms( $post_id, 'silverpage-category');
		$category_id = $category[0]->term_id;
		
	}	
	if($post_id != 0){
		$single_query = " AND post_id=$post_id";
	}
	
	if(!empty($category_near)){
		$cat_query = " AND {$wpdb->prefix}term_relationships.term_taxonomy_id = ".$category_near;
		$child_id = ','.$category_near;
	}
	if(!empty($category_detail)){
		$cat_query = " AND {$wpdb->prefix}term_relationships.term_taxonomy_id = ".$category_detail;
		$child_id = ','.$category_detail;
	}
	
	$where = " WHERE posts.ID NOT IN ('" . implode( "', '" , $promotion_ids ) . "') AND post_type = 'silverpage' $single_query AND post_status = 'publish' AND {$wpdb->prefix}term_taxonomy.term_id IN ($category_id $child_id)";
	$join = " INNER JOIN {$wpdb->prefix}posts AS posts ON ( posts.ID = {$wpdb->prefix}location_latlong.post_id ) 
INNER JOIN {$wpdb->prefix}term_relationships ON (posts.ID = {$wpdb->prefix}term_relationships.object_id) 
INNER JOIN {$wpdb->prefix}term_taxonomy ON ({$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id $cat_query)";
	
	if (!empty($latitude) || !empty($longitude)) {	
	$sql = "SELECT DISTINCT post_id, Min( 3959 * ACOS( COS( RADIANS( $latitude ) ) * COS( RADIANS( location_latitude ) ) 
		* COS( RADIANS( location_longitude ) - RADIANS( $longitude ) ) + SIN( RADIANS( $latitude ) ) * SIN( RADIANS( location_latitude ) ) ) ) AS distance
		FROM {$wpdb->prefix}location_latlong";
	}
	if(!empty($category_id)){
		$sql .= $join;
		$sql .= $where;
	}
	$sql .= " GROUP BY post_id";
	if(!empty($distance) && $distance != 0){
		$sql .= " HAVING distance < ".$distance;	
	}
	$sql .= " ORDER BY distance ASC";
	
	$result = $wpdb->get_results($sql,ARRAY_A);	
	
	$all_promotions = array_merge($featured_result, $result);
	
	return $all_promotions;

}

// Get post list and distance 
/*function get_location_distance($latitude,$longitude,$distance=0){
	global $wpdb,$post;
	
	$category_id = get_queried_object_id();
	$category_near = $_REQUEST['category-near'];
	$category_detail = $_REQUEST['category-detail'];
	
	
    if ( is_category($category_id) ) {
		$category_id = $category_id;
    }	
	if(is_singular('silverpage')){
		$post_id = $post->ID;
		$category = wp_get_post_terms( $post_id, 'silverpage-category');
		$category_id = $category[0]->term_id;
		
	}	
	if($post_id != 0){
		$single_query = " AND post_id=$post_id";
	}
	
	if(!empty($category_near)){
		$cat_query = " AND {$wpdb->prefix}term_relationships.term_taxonomy_id = ".$category_near;
		$child_id = ','.$category_near;
	}
	if(!empty($category_detail)){
		$cat_query = " AND {$wpdb->prefix}term_relationships.term_taxonomy_id = ".$category_detail;
		$child_id = ','.$category_detail;
	}
	
	$where = " WHERE post_type = 'silverpage' $single_query AND post_status = 'publish' AND {$wpdb->prefix}term_taxonomy.term_id IN ($category_id $child_id)";
	$join = " INNER JOIN {$wpdb->prefix}posts AS posts ON ( posts.ID = {$wpdb->prefix}location_latlong.post_id ) 
INNER JOIN {$wpdb->prefix}term_relationships ON (posts.ID = {$wpdb->prefix}term_relationships.object_id) 
INNER JOIN {$wpdb->prefix}term_taxonomy ON ({$wpdb->prefix}term_relationships.term_taxonomy_id = {$wpdb->prefix}term_taxonomy.term_taxonomy_id $cat_query)";
	
	if (!empty($latitude) || !empty($longitude)) {	
	$sql = "SELECT DISTINCT post_id, Min( 3959 * ACOS( COS( RADIANS( $latitude ) ) * COS( RADIANS( location_latitude ) ) 
		* COS( RADIANS( location_longitude ) - RADIANS( $longitude ) ) + SIN( RADIANS( $latitude ) ) * SIN( RADIANS( location_latitude ) ) ) ) AS distance
		FROM {$wpdb->prefix}location_latlong";
	}
	if(!empty($category_id)){
		$sql .= $join;
		$sql .= $where;
	}
	$sql .= " GROUP BY post_id";
	if(!empty($distance) && $distance != 0){
		$sql .= " HAVING distance < ".$distance;	
	}
	$sql .= " ORDER BY distance ASC";
	// if($_SERVER['REMOTE_ADDR'] == '103.251.217.63'){
	// 	echo $sql;
	// 	exit;
	// }
	$result = $wpdb->get_results($sql,ARRAY_A);	
	return $result;
}*/

// Get client IP address

function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// Get client IP address
function get_current_location(){
	$PublicIP = get_client_ip(); 
 
 //$location_data = file_get_contents("https://ipapi.co/$PublicIP/json/");
 //print_r($PublicIP);
 if((!empty($PublicIP)) && (strpos($PublicIP, ',') !== false)){
	 $PublicIP=explode(',',$PublicIP);
	 $PublicIP=$PublicIP[0];
 }
 
 $location_data = file_get_contents("http://ip-api.com/json/$PublicIP"); 
 //print_r($location_data);
 $location_data = json_decode($location_data,true);
 $location['lat'] = $location_data['lat'];
 $location['lng'] = $location_data['lon'];
 
 return $location;
}

/* get lat long using POST CODE / Address*/
function getLatLng($address) {
	
  $geoData = array();
  if(!empty($address)){
	   $geo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyAQRFhih2cp0o-EW9s4FfWBr1Ld4uTnyh0&address='.urlencode($address).',US&sensor=false');
    $geo = json_decode($geo, true);
	if ($geo['status'] = 'OK') {
      $geoData['lat'] = $geo['results'][0]['geometry']['location']['lat'];
      $geoData['lng'] = $geo['results'][0]['geometry']['location']['lng'];
    }	
  }
  return $geoData;
}

add_action('before_delete_post', 'delete_post_attachments');
function delete_post_attachments($post_id){

    global $post_type;   
    if($post_type !== 'post') return;

    global $wpdb;

    $args = array(
        'post_type'         => 'attachment',
        'post_status'       => 'any',
        'posts_per_page'    => -1,
        'post_parent'       => $post_id
    );
    $attachments = new WP_Query($args);
    $attachment_ids = array();
    if($attachments->have_posts()) : while($attachments->have_posts()) : $attachments->the_post();
            $attachment_ids[] = get_the_id();
        endwhile;
    endif;
    wp_reset_postdata();

    if(!empty($attachment_ids)) :
        $delete_attachments_query = $wpdb->prepare('DELETE FROM %1$s WHERE %1$s.ID IN (%2$s)', $wpdb->posts, join(',', $attachment_ids));
        $wpdb->query($delete_attachments_query);
    endif;

}
add_action('wp_ajax_nopriv_beacon_action_add_events','beacon_action_add_events');
add_action('wp_ajax_beacon_action_add_events','beacon_action_add_events');
function beacon_action_add_events(){
	$events_data = $_REQUEST;
		
    $event_title = $events_data['event-name'];
    $event_category = $events_data['category-region'];
    $event_content = $events_data['event-content'];	
    $event_address = $events_data['address'];
    $event_city = $events_data['city'];
    $event_state = $events_data['category-state'];
    $event_zipcode = $events_data['zipcode'];
    $event_fromdate = $events_data['fromdate'];
	$event_todate = $events_data['todate'];
	$event_fromtime = $events_data['fromtime'];
	$event_totime = $events_data['totime'];
	
	if(!empty($event_title)){
		$post_date = array(
				'post_title' => $event_title,
				'post_type' => 'events',	
				'post_status' => 'publish',
		);
		$post_id = wp_insert_post( $post_date );
		if(!empty($post_id)){
			wp_set_object_terms( $post_id, (int)$event_category, 'events-category' );
			
			$post_metadata = array(
				'events_status' => 'disapprove',
				'events_content' => $event_content,
				'events_address' => $event_address,
				'events_city' => $event_city,
				'events_state' => $event_state,
				'events_zipcode' => $event_zipcode,
				'events_from_date' => $event_fromdate,
				'events_to_date' => $event_todate,
				'events_from_time' => $event_fromtime,
				'events_to_time' => $event_totime,				
			);
			
			foreach($post_metadata as $meta_key => $meta_value){
				update_field($meta_key,$meta_value,$post_id);
			}		
				$catdata = get_term_by('id', (int)$event_category, 'events-category');
				$category_name = $catdata->name;
		
				$logo = get_template_directory_uri().'/images/logo.png';
				$ssl_data = ( ! is_ssl() ) ? 'http' : 'https' ;
				$approvelink = admin_url('post.php?post=' . $post_id .'&action=edit', $ssl_data);
				
				$message_template = file_get_contents(get_template_directory().'/includes/email-template/events_approval.html');
				
				$mail_message_admin = str_replace(
						array("[emaillogo]","[event_title]","[event_category]","[event_content]","[event_address]","[event_city]","[event_state]","[event_zipcode]","[event_fromdate]","[event_todate]","[event_fromtime]","[event_totime]","[click_here]"),
						array($logo,$event_title,$category_name ,$event_content ,$event_address ,$event_city ,$event_state ,$event_zipcode ,$event_fromdate ,$event_todate , $event_fromtime, $event_totime ,$approvelink), 
						$message_template
						);
				
				$admin_email = get_option('admin_email');
				$blog_info = get_bloginfo();
				$headers[] = "From: ".$blog_info." <$admin_email>";    
				$headers[] = 'Content-type: text/html; ' . "\r\n";
				$mail = wp_mail($admin_email, 'New Events Registration', $mail_message_admin, $headers);
				
				if($mail){
					$content = array('result' => 'success', 'message' => 'You have successfully submitted an event. Once the admin approves, it will be shown here.');					
					
				}else{
					$content = array('result' => 'fail', 'message' => 'There is some issue please try agian.');
				}
				
				$content = json_encode( $content );
				
				
		}
		
	}
	
	//print_r($events_data);
	die($content);
}

function get_events_html($events_id){
	
	$events_title = get_the_title($events_id);
	$events_content = get_field('events_content',$events_id);
	$events_address = get_field('events_address',$events_id);
	$events_city = get_field('events_city',$events_id);
	$events_state = get_field('events_state',$events_id);
	$events_zipcode = get_field('events_zipcode',$events_id);
	$events_from_date = get_field('events_from_date',$events_id);
	$events_to_date = get_field('events_to_date',$events_id);
	$events_from_time = get_field('events_from_time',$events_id);
	$events_to_time = get_field('events_to_time',$events_id);
	
	
	$html_data = '';
	
			$html_data = '<div class="col-md-12">';
				$html_data .= '<div class="event-list-single">';
					$html_data .= '<h2>'.$events_title.'</h2>';
					$html_data .= '<h4>'.$events_from_date;
					if(!empty($events_to_date)){	
						$html_data .= ' to '.$events_to_date;
					}
					$html_data .= ' <time>'.$events_from_time;
					if(!empty($events_to_time)){
						$html_data .= ' to '.$events_to_time;
					}
					$html_data .= '</time></h4>';					
					if(!empty($events_address) || !empty($events_city) || !empty($events_state) || !empty($events_zipcode)){
						$html_data .= '<address>';
							if(!empty($events_address)){
								$html_data .= $events_address;
							}
							if(!empty($events_city)){
								$html_data .= ', '.$events_city;
							}
							if(!empty($events_state)){
								$html_data .= ', '.$events_state;
							}
							if(!empty($events_zipcode)){
								$html_data .= ' '.$events_zipcode;
							}							
						$html_data .= '</address>';
					}
					if(!empty($events_content)){
						$html_data .= '<p>'.$events_content.'</p>';
					}
				$html_data .= '</div>';
			$html_data .= '</div>';
			
	return $html_data;	
}

// Add Custom Bulk Action
add_filter( 'bulk_actions-edit-events', 'register_my_bulk_actions' ); 
function register_my_bulk_actions($bulk_actions) {
  $bulk_actions['approve'] = __( 'Approve', 'approve');
  $bulk_actions['disapprove'] = __( 'Disapprove', 'disapprove');
  return $bulk_actions;
}

// Process Custom Bulk Action
add_filter( 'handle_bulk_actions-edit-events', 'my_bulk_action_handler', 10, 3 ); 
function my_bulk_action_handler( $redirect_to, $doaction, $post_ids ) {
	

	if ( $doaction == 'approve' ) {     
		foreach ( $post_ids as $post_id ) {
			update_field('events_status','approve',$post_id);
		}
		$redirect_to = add_query_arg( 'approve', count( $post_ids ), $redirect_to );
	}elseif ( $doaction == 'disapprove' ) {     
		foreach ( $post_ids as $post_id ) {
			update_field('events_status','disapprove',$post_id);
		}
		$redirect_to = add_query_arg( 'disapprove', count( $post_ids ), $redirect_to );
	}else{
		return $redirect_to;
	}
	return $redirect_to;
}

// Add Custom Events status Column
add_filter( 'manage_events_posts_columns', 'set_custom_edit_events_columns' );
add_action( 'manage_events_posts_custom_column' , 'custom_events_column', 10, 2 );

function set_custom_edit_events_columns($columns) {
    $columns['status'] = __( 'Status', 'beacon' );

    return $columns;
}

function custom_events_column( $column, $post_id ) {
    switch ( $column ) {

        case 'status' :
            echo get_post_meta( $post_id , 'events_status' , true ); 
            break;

    }
}

// Add Custom Views and Filter
add_filter('views_edit-events','events_approval_notapproval_filter');
function events_approval_notapproval_filter($views) {
	 global $wp_query;
	 
		$approve_query = array(
            'post_type'   => 'events',
            'post_status' => 'publish',
            'meta_query'  => array(					
					array(
						'key'     => 'events_status',
						'value'   => 'approve',
						'compare' => '=',
					),					
				)
			);
 
        $approve_result = new WP_Query($approve_query);
		$approve_class = ($wp_query->query_vars['events_status'] == 'approve') ? ' class="current"' : '';
		$views['approve'] = '<a '.$approve_class.' href="'.admin_url('edit.php?post_type=events&events_status=approve').'">Approve<span class="count"> ('.$approve_result->found_posts.')</span></a>';
		
		$disapprove_query = array(
            'post_type'   => 'events',
            'post_status' => 'publish',
            'meta_query'  => array(					
					array(
						'key'     => 'events_status',
						'value'   => 'disapprove',
						'compare' => '=',
					),					
				)
			);
 
        $disapprove_result = new WP_Query($disapprove_query);
		$disapprove_class = ($wp_query->query_vars['events_status'] == 'disapprove') ? ' class="current"' : ''; 
		$views['disapprove'] = '<a '.$disapprove_class.' href="'.admin_url('edit.php?post_type=events&events_status=disapprove').'">Not approve<span class="count"> ('.$disapprove_result->found_posts.')</span></a>';
		
        return $views;
	
}

// Add custom query vars
add_filter('query_vars', 'beacon_events_register_query_vars' );
function beacon_events_register_query_vars( $qvars ){
    $qvars[] = 'events_status';
    return $qvars;
}

// Filter Query 
add_action( 'pre_get_posts', 'wpse57351_pre_get_posts' );
function wpse57351_pre_get_posts( $query ) {
	
	if(is_admin() && $query->query['post_type'] == 'events'){
		
	    $events_status = $query->get('events_status');
		if( !empty($events_status) ){
			$meta_query = $query->get('meta_query');
			if( empty($meta_query) )
				$meta_query = array();
	
			$meta_query[] = array(
				'key' => 'events_status',
				'value' => $events_status,
				'compare' => '=',
			);
			$query->set('meta_query',$meta_query);
	
		}
	}
}

function add_silver_2_1_3($area, $ads_array = array() ) {

	if ( count($ads_array) > 0 ) {

		global $wpdb;
		//$post_id = $_POST['post_id'];
		$client_ip = get_client_ip();

		$table_name2 = $wpdb->prefix.'analytics';
		$timestamp = date("Y-m-d H:i:s");

		foreach ($ads_array as $key => $post_id) {
		

				$records = $wpdb->get_results("SELECT * FROM $table_name2 where userip = '".$client_ip."' AND postid = $post_id AND type = 3 AND area = '".$area."'");

				if(count($records) > 0) {
					$analytics_query = "INSERT INTO $table_name2( postid, posttype, type, userip, created_date, area )
					VALUES ( $post_id, 2, 1, '$client_ip','$timestamp', '$area')";

					$analytics_result = $wpdb->query($analytics_query);	
				} else {
					$analytics_query = "INSERT INTO $table_name2( postid, posttype, type, userip, created_date, area )
					VALUES ( $post_id, 2, 1, '$client_ip','$timestamp', '$area'), ( $post_id, 2, 3, '$client_ip','$timestamp', '$area')";
					/*echo "<pre>";
					print_r($records);
					echo "</pre>";*/
					//exit;
					$analytics_result = $wpdb->query($analytics_query);
				}							
		}
	}
}

function add_silver_1_2_3($post_id){
	if ( get_post_type( $post_id ) == 'silverpage' ) {

		global $wpdb;
		//$post_id = $_POST['post_id'];
		$client_ip = get_client_ip();

		$table_name2 = $wpdb->prefix.'analytics';
		$timestamp = date("Y-m-d H:i:s");

		if(!empty($post_id)){

				$records = $wpdb->get_results("SELECT * FROM $table_name2 where userip = '".$client_ip."' AND postid = $post_id AND type = 3");
				if(count($records) > 0) {
					$analytics_query = "INSERT INTO $table_name2( postid, posttype, type, userip, created_date )
					VALUES ( $post_id, 1, 1, '$client_ip','$timestamp')";

					$analytics_result = $wpdb->query($analytics_query);	
				} else {
					$analytics_query = "INSERT INTO $table_name2( postid, posttype, type, userip, created_date )
					VALUES ( $post_id, 1, 1, '$client_ip','$timestamp'), ( $post_id, 1, 3, '$client_ip','$timestamp')";

					$analytics_result = $wpdb->query($analytics_query);
				}							
		}
	}
}

add_action('wp_ajax_nopriv_ads_click_analytics','ads_click_analytics');
add_action('wp_ajax_ads_click_analytics','ads_click_analytics');
function ads_click_analytics(){
	global $wpdb;
	$post_id = $_POST['post_id'];
	$area = $_POST['area'];
	$client_ip = get_client_ip();

	$table_name2 = $wpdb->prefix.'analytics';
	$timestamp = date("Y-m-d H:i:s");

	if(!empty($post_id)){
		
			$analytics_query = "INSERT INTO $table_name2( postid, posttype, type, userip, created_date, area )
			VALUES ( $post_id, 2, 2, '$client_ip','$timestamp', '$area')";

			$analytics_result = $wpdb->query($analytics_query);			
	}
	die();
}

add_action('wp_ajax_nopriv_beacon_sliver_page_tracking','beacon_sliver_page_tracking');
add_action('wp_ajax_beacon_sliver_page_tracking','beacon_sliver_page_tracking');
function beacon_sliver_page_tracking(){
	global $wpdb;
	$table_name = $wpdb->prefix.'sliverpage_tracking';
	$post_id = $_POST['post_id'];
	$event_type = $_POST['event_type'];
	$client_ip = get_client_ip();

	$table_name2 = $wpdb->prefix.'analytics';
	$timestamp = date("Y-m-d H:i:s");
	
	if(!empty($post_id)){
		$sql_query = "INSERT INTO $table_name( post_id,	user_ip,click_events )
			VALUES ( '$post_id','$client_ip','$event_type')";

		$result = $wpdb->query($sql_query);

		if($event_type == 'Click to Phone' || $event_type == 'Click to MAP' || $event_type == 'Visit Website'){
			$analytics_query = "INSERT INTO $table_name2( postid, posttype, type, userip, created_date )
			VALUES ( $post_id, 1, 2, '$client_ip','$timestamp')";

			$analytics_result = $wpdb->query($analytics_query);	
		}
	}
	die();
}

add_action( 'pre_get_posts', 'beacon_exlude_search' );
function beacon_exlude_search( $query ) { 
 if( is_admin() || ! $query->is_main_query() )
 return; 
	 if( $query->is_search() ) {
		$in_search_post_types = get_post_types( array( 'exclude_from_search' => false ) );

		$post_type_to_remove = 'silverpage';
		if( is_array( $in_search_post_types ) && in_array( $post_type_to_remove, $in_search_post_types ) ) {
			unset( $in_search_post_types[ $post_type_to_remove ] );
			$query->set( 'post_type', $in_search_post_types );
		}
	 }
}


/* Start custom gravity forms */
/* Custom gravity forms - Save forms records */
add_action('gform_after_submission', 'beacon_ads_add_entry_to_db', 10, 2);
function beacon_ads_add_entry_to_db($data, $form) {
	
	$entry_id = $data['id'];
	$form_id = $data['form_id'];
	$cname = $data[1];
	$email = $data[2];
	$phone = preg_replace("/[^0-9]/", "", $data[3]);
	
	$editions = "";
	$j = 0;
	for($i=1;$i<=4;$i++) {
		if(!empty($data['4.'.$i])) {
			if($j == 0) {
				$editions .= $data['4.'.$i];
			} else {
				$editions .= ','.$data['4.'.$i];
			}
			$j++;
		}
	}
	
	//$months = json_decode($data[12],true);	
	$no_of_month = $data[16];
	$months = $data[15];
	$adver_month = array();
	$cmonth = date('m');
	if( $months >= $cmonth )
	{	
		$adver_month[0] = date("Y-m", mktime(0, 0, 0, $months, 10));
		for ($i = 0; $i<($no_of_month-1); ++$i) {
	    	$adver_month[$i+1] = date("Y-m", mktime(0, 0, 0, $months+$i+1, 10));
		}
		$adver_month = implode(",",$adver_month);
	} else {
		$nextyear = date('Y', strtotime('+1 year'));

		$adver_month[0] = $nextyear."-".date("m", mktime(0, 0, 0, $months, 10));
		for ($i = 0; $i<($no_of_month-1); ++$i) {
	    	$adver_month[$i+1] = $nextyear."-".date("m", mktime(0, 0, 0, $months+$i+1, 10));
		}
		$adver_month = implode(",",$adver_month);
	}

	$category = $data[6];
	$adtext = $data[7];
	$payment = $data[9];
	$createdate = $data['date_created'];
	$submit_url = $data['source_url'];	
	
  	global $wpdb;
  
  	// add form data to custom database table
	$wpdb->insert(
	    'wp_ewmw5yx03z_bnoc',
	    array(
			'entry_id' => $entry_id,
			'form_id' => $form_id,
			'contactname' => $cname,
			'email' => $email,
			'phone' => $phone,
			'editions' => $editions,
			'no_of_months'=>$no_of_month,
			'category' => $category,
			'adtext' => $adtext,
			'payment' => $payment,
			'createdate' => $createdate,
			'submit_url' => $submit_url,
			'months'=>$months,
			'advertise_month'=>$adver_month
	    )
	);
	
}

/* Custom gravity forms in Custom table- Update records */
add_action('gform_after_update_entry', 'beacon_ads_update_entry_to_db', 10, 3);
function beacon_ads_update_entry_to_db($form, $entry_id) {
	global $wpdb;

	$entries = GFAPI::get_entry( $entry_id );
	
	$form_id = $entries['form_id'];
	$cname = $entries[1];
	$email = $entries[2];
	$phone = preg_replace("/[^0-9]/", "", $entries[3]);
	
	$editions = "";
	$j = 0;
	for($i=1;$i<=4;$i++) {
		if(!empty($entries['4.'.$i])) {
			if($j == 0) {
				$editions .= $entries['4.'.$i];
			} else {
				$editions .= ','.$entries['4.'.$i];
			}
			$j++;
		}
	}
	
	//$months = $entries[5];
	$no_of_month = $entries[16];
	$months = $entries[15];
	$adver_month = array();
	$cmonth = date('m');
	if( $months >= $cmonth )
	{	
		$adver_month[0] = date("Y-m", mktime(0, 0, 0, $months, 10));
		for ($i = 0; $i<($no_of_month-1); ++$i) {
	    	$adver_month[$i+1] = date("Y-m", mktime(0, 0, 0, $months+$i+1, 10));
		}
		$adver_month = implode(",",$adver_month);
	} else {
		$nextyear = date('Y', strtotime('+1 year'));

		$adver_month[0] = $nextyear."-".date("m", mktime(0, 0, 0, $months, 10));
		for ($i = 0; $i<($no_of_month-1); ++$i) {
	    	$adver_month[$i+1] = $nextyear."-".date("m", mktime(0, 0, 0, $months+$i+1, 10));
		}
		$adver_month = implode(",",$adver_month);
	}

	
	$category = $entries[6];
	$adtext = $entries[7];
	$payment = $entries[9];
	$createdate = $entries['date_created'];
	$submit_url = $entries['source_url'];

	$records = $wpdb->get_results("SELECT * FROM wp_ewmw5yx03z_bnoc where entry_id = '".$entry_id."'");
	$count = count($records);

	if($count > 0) {
		$wpdb->update("wp_ewmw5yx03z_bnoc", array(
			'form_id' => $form_id,
			'contactname' => $cname,
			'email' => $email,
			'phone' => $phone,
			'editions' => $editions,
			'no_of_months'=>$no_of_month,
			'category' => $category,
			'adtext' => $adtext,
			'payment' => $payment,
			'createdate' => $createdate,
			'submit_url' => $submit_url,
			'months'=> $months,
			'advertise_month'=> $adver_month
		), array('entry_id' => $entry_id));
	} else {
		$wpdb->insert(
			'wp_ewmw5yx03z_bnoc',
			array(
				'entry_id' => $entry_id,
				'form_id' => $form_id,
				'contactname' => $cname,
				'email' => $email,
				'phone' => $phone,
				'editions' => $editions,
				'no_of_months'=>$no_of_month,
				'category' => $category,
				'adtext' => $adtext,
				'payment' => $payment,
				'createdate' => $createdate,
				'submit_url' => $submit_url,
				'months'=> $months,
				'advertise_month'=> $adver_month
			)
		);
	}
}

// Custom gravity form in custom table - Delete records
add_action('gform_delete_entry', 'delete_entry_post');
function delete_entry_post( $entry_id ) {
	global $wpdb;
	
	$wpdb->query("DELETE FROM wp_ewmw5yx03z_bnoc WHERE entry_id='$entry_id'");

}

// Advertisement - Get Ad Text Prices
add_action('wp_ajax_beacon_adtext_prices', 'beacon_adtext_prices');
add_action('wp_ajax_nopriv_beacon_adtext_prices', 'beacon_adtext_prices');
function beacon_adtext_prices() {
	$data = get_field('ad_text_prices', 'option');
	echo json_encode(array('success' => true, 'result' => $data));
	exit();
}

// Advertisement menu
add_action( 'admin_menu', 'beacon_advertisement_menu' );

// Add menu in sidebar
function beacon_advertisement_menu() {
	add_menu_page('Advertisement', 'Advertisement', 'manage_options', 'advertisement', 'beacon_my_advertisement' );
}

// Advertisement display listing data
function beacon_my_advertisement() {
	global $wpdb;

	$edition = "";
	if(!empty($_REQUEST['seledition']) || !empty($_REQUEST['selcategories']) || !empty($_REQUEST['monthyear'])) {
		
		$yearandmonth = explode("-",$_REQUEST['monthyear']);
		$searchyear = $yearandmonth[0];
		$searchmonth = $yearandmonth[1];
		$edition = wp_trim_words($_REQUEST['seledition']);
		$category = wp_trim_words($_REQUEST['selcategories']);
		
		$monthyear = $_REQUEST['monthyear'];
		if($monthyear) {
		    $monthyear = date('Y-m',strtotime($monthyear));
		    //$monthyear = $monthyear.'-01';
		} else {
		    $monthyear = "";
		}

		$where = " where";
		$var1 = "";
		$var2 = "";
		$var3 = "";
		if(!empty($edition)) { 
			$andedition = "";
			if(!empty($category) || !empty($monthyear)) { 
				$andedition = " AND"; 
			}
			$var1 = " editions LIKE '%".$edition."%'".$andedition."";
		} 
		if(!empty($category)) { 
			$andcategory = "";
			if(!empty($monthyear)) { 
				$andcategory = " AND"; 
			}
			$var2 = " category LIKE '%".$category."'".$andcategory."";
		}

		if(!empty($monthyear)) { 
			//$var3 = " (DATE_FORMAT(createdate,'%Y-%m-%d') + INTERVAL no_of_months MONTH) >= '".$monthyear."'"; 
			//$var3 = " '".$monthyear."' >= (DATE_FORMAT(createdate,'%Y-%m')) AND '".$monthyear."' <= DATE_FORMAT((DATE_FORMAT(createdate,'%Y-%m-%d') + INTERVAL no_of_months MONTH),'%Y-%m')";

			$var3 = " ADVERTISE_MONTH LIKE '%".$monthyear."%' ";
		}
		
		//$getEditions = get_field('editions', 'option');		
		$data = $wpdb->get_results("SELECT *,DATE_FORMAT(createdate,'%Y-%m-%d') AS createddate,(DATE_FORMAT(createdate,'%Y-%m-%d') + INTERVAL no_of_months MONTH) AS lastdate FROM wp_ewmw5yx03z_bnoc ".$where.$var1.$var2.$var3." order by createdate desc");
	} else {
		$data = $wpdb->get_results("SELECT *,DATE_FORMAT(createdate,'%Y-%m-%d') AS createddate,(DATE_FORMAT(createdate,'%Y-%m-%d') + INTERVAL no_of_months MONTH) AS lastdate FROM wp_ewmw5yx03z_bnoc order by createdate desc");
	}

	$count = count($data);

	echo '<div class="wrap"><h2>'. __('Listing Advertisement') .'</h2></div>';
	?>
    	<form method="get" id="searchform" action="<?php echo esc_url( admin_url( '/admin.php' ) ); ?>">
    		<input type="hidden" name="page" value="advertisement">
    		<select name="seledition" id="seledition">
				<option value="">Select Editions</option>
            	<option value="Baltimore Beacon" <?php selected( $_REQUEST['seledition'] , 'Baltimore Beacon'); ?>>Baltimore Beacon</option>
            	<option value="Howard County Beacon" <?php selected( $_REQUEST['seledition'] , 'Howard County Beacon'); ?>>Howard County Beacon</option>
            	<option value="Washington Beacon" <?php selected( $_REQUEST['seledition'] , 'Washington Beacon'); ?>>Washington Beacon</option>
            	<option value="Fifty Plus Richmond" <?php selected( $_REQUEST['seledition'] , 'Fifty Plus Richmond'); ?>>Fifty Plus Richmond</option>
	        </select>

			<select name="selcategories">
	        	<option value="">Select Categories</option>
	            <option value="Business & Employment Opportunities" <?php selected( $_REQUEST['selcategories'] , 'Business & Employment Opportunities'); ?>>Business & Employment Opportunities</option>
	            <option value="Caregivers" <?php selected( $_REQUEST['selcategories'] , 'Caregivers'); ?>>Caregivers</option>
	            <option value="Computer Services" <?php selected( $_REQUEST['selcategories'] , 'Computer Services'); ?>>Computer Services</option>
	            <option value="Entertainment" <?php selected( $_REQUEST['selcategories'] , 'Entertainment'); ?>>Entertainment</option>
	            <option value="Financial" <?php selected( $_REQUEST['selcategories'] , 'Financial'); ?>>Financial</option>
	            <option value="For Sale" <?php selected( $_REQUEST['selcategories'] , 'For Sale'); ?>>For Sale</option>
	            <option value="For Sale/Rent Real Estate" <?php selected( $_REQUEST['selcategories'] , 'For Sale/Rent Real Estate'); ?>>For Sale/Rent Real Estate</option>
	            <option value="Health" <?php selected( $_REQUEST['selcategories'] , 'Health'); ?>>Health</option>
	            <option value="Home/Handyman Services" <?php selected( $_REQUEST['selcategories'] , 'Home/Handyman Services'); ?>>Home/Handyman Services</option>
	            <option value="Legal Services" <?php selected( $_REQUEST['selcategories'] , 'Legal Services'); ?>>Legal Services</option>
	            <option value="Miscellaneous" <?php selected( $_REQUEST['selcategories'] , 'Miscellaneous'); ?>>Miscellaneous</option>
	            <option value="Personals" <?php selected( $_REQUEST['selcategories'] , 'Personals'); ?>>Personals</option>
	            <option value="Personal Services" <?php selected( $_REQUEST['selcategories'] , 'Personal Services'); ?>>Personal Services</option>
	            <option value="TV/Cable" <?php selected( $_REQUEST['selcategories'] , 'TV/Cable'); ?>>TV/Cable</option>
	            <option value="Vacation Opportunities" <?php selected( $_REQUEST['selcategories'] , 'Vacation Opportunities'); ?>>Vacation Opportunities</option>
	            <option value="Wanted" <?php selected( $_REQUEST['selcategories'] , 'Wanted'); ?>>Wanted</option>
	        </select>

	        <input type="text" id="custom_monthyear" name="monthyear" placeholder="Select Month" value="<?php if(!empty($_REQUEST['monthyear'])) { echo $_REQUEST['monthyear']; } ?>" />

			<input type="submit" class="button" name="search" id="searchsubmit" value="search" />

			<?php if($count > 0) { ?>
				<a href="<?php echo admin_url(); ?>/admin-ajax.php?action=beacon_export_advertisement<?php if(!empty($_REQUEST['seledition'])) { echo '&seledition='.$_REQUEST['seledition']; } if(!empty($_REQUEST['selcategories'])) { echo '&selcategories='.$_REQUEST['selcategories']; } if(!empty($_REQUEST['monthyear'])) { echo '&monthyear='.$_REQUEST['monthyear']; } ?>">
					<strong>DOWNLOAD</strong>
				</a>
			<?php } ?>

		</form>

		<table class="wp-list-table widefat fixed posts">
			<thead>
				<tr>
					<th><?php _e('No', 'pippinw'); ?></th>
					<th><?php _e('Contact Name', 'pippinw'); ?></th>
					<th><?php _e('Email', 'pippinw'); ?></th>
					<th><?php _e('Phone', 'pippinw'); ?></th>
					<th><?php _e('Editions', 'pippinw'); ?></th>
					<th><?php _e('Category', 'pippinw'); ?></th>
					<th><?php _e('Selected Months', 'pippinw'); ?></th>
					<th><?php _e('Starting Month', 'pippinw'); ?></th>	
					<th><?php _e('Total Cost of Advertisement', 'pippinw'); ?></th>	
					<th><?php _e('Advertise Month', 'pippinw'); ?></th>	
					<th><?php _e('Created Date', 'pippinw'); ?></th>
					<th><?php _e('Status', 'pippinw'); ?></th>					
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th><?php _e('No', 'pippinw'); ?></th>
					<th><?php _e('Contact Name', 'pippinw'); ?></th>
					<th><?php _e('Email', 'pippinw'); ?></th>
					<th><?php _e('Phone', 'pippinw'); ?></th>
					<th><?php _e('Editions', 'pippinw'); ?></th>
					<th><?php _e('Category', 'pippinw'); ?></th>
					<th><?php _e('Selected Months', 'pippinw'); ?></th>
					<th><?php _e('Starting Month', 'pippinw'); ?></th>		
					<th><?php _e('Total Cost of Advertisement', 'pippinw'); ?></th>		
					<th><?php _e('Advertise Month', 'pippinw'); ?></th>			
					<th><?php _e('Created Date', 'pippinw'); ?></th>
					<th><?php _e('Status', 'pippinw'); ?></th>					
				</tr>
			</tfoot>
			<tbody>
				<?php
					if($count > 0) {
						$i = 0;
						foreach($data as $row) {
							$varedition = "";							
							if(!empty($edition)) {
								$varedition = $edition;
							} else {
								$varedition = $row->editions;	
							}

							// Display name of months
							$startdate = (new DateTime($row->createddate))->modify('first day of this month');
							$enddate = (new DateTime($row->lastdate))->modify('first day of next month');
							$interval = DateInterval::createFromDateString('1 month');
							$period   = new DatePeriod($startdate, $interval, $enddate);
							//echo "Test123".date('M', strtotime('+'.$row->no_of_months.' month', strtotime(date("M",11)));
							

							
						/*	$nameofmonths = array();
							$nameofmonths[0] = date("M", mktime(0, 0, 0, $row->months, 10));
							for ($i = 0; $i<($row->no_of_months-1); ++$i) {
							    $nameofmonths[$i+1] = date("M", mktime(0, 0, 0, $row->months+$i+1, 10));
							}
							$nameofmonths = implode(",",$nameofmonths);*/
							$period =  explode(",",$row->advertise_month);

							$nameofmonths = "";
							
							foreach ($period as $dt) {
								$selectmonts = explode("-",$dt);
							    //$nameofmonths[] =  date("M", mktime(0, 0, 0, $selectmonts[1], 10))."-".$selectmonts[0]."<br/>";
							    $nameofmonths .=  date("M", mktime(0, 0, 0, $selectmonts[1], 10))."-".$selectmonts[0]."<br/>";
							}
							

							$selmonths = [];
							if( $row->months != 0 ){
								$selectmonts = explode(",",$row->months);

								foreach ($selectmonts as $dt) {									
								    $selmonths[] = date("M", mktime(0, 0, 0, $dt, 10));
								}
								$selmonths = implode(",",$selmonths);
							}

							?>
								<tr <?php if($i % 2 == 0) { echo 'class="alternate"'; } ?>>
									<td><?php _e($i+1, 'pippinw'); ?></td>
									<td><?php _e($row->contactname, 'pippinw'); ?></td>
									<td><?php _e($row->email, 'pippinw'); ?></td>
									<td><?php _e($row->phone, 'pippinw'); ?></td>
									<td><?php _e($varedition, 'pippinw'); ?></td>
									<td><?php _e($row->category, 'pippinw'); ?></td>
									<td><?php _e($row->no_of_months, 'pippinw'); ?></td> 
									<td><?php _e($selmonths, 'pippinw'); ?></td>
									<td>$<?php _e($row->payment, 'pippinw'); ?></td>
									<td><?php _e($nameofmonths, 'pippinw'); ?></td>
									<td><?php _e($row->createddate, 'pippinw'); ?></td>
									<td><?php _e(ucfirst($row->status), 'pippinw'); ?></td>
									
								</tr>
							<?php 
							$i++; 
						} 
					} else {
						echo "<tr colspan='5'><td>No record found.</td></tr>";
					}
					
				?>
			</tbody>
		</table>
	<?php
}

// Advertisement - Download csv file
function beacon_export_advertisement() {
	global $wpdb;

	$filename = "export_".date("Y.m.d").".csv";
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header('Content-type: application/csv; charset=UTF-8');
	header('Content-Disposition: attachment; filename="'.$filename.'"');

	$output = fopen("php://output", "w");

	fputcsv($output, array('bnoc_ID', 'contactname', 'email', 'phone', 'editions', 'category', 'adtext', 'payment', 'selected months','starting month','advertise month','createdate','status','submit_url'));

	$edition = "";
	if(!empty($_REQUEST['seledition']) || !empty($_REQUEST['selcategories']) || !empty($_REQUEST['monthyear'])) {
		$yearandmonth = explode("-",$_REQUEST['monthyear']);
		$searchyear = $yearandmonth[0];
		$searchmonth = $yearandmonth[1];

		$edition = wp_trim_words($_REQUEST['seledition']);
		$category = wp_trim_words($_REQUEST['selcategories']);
		if($category == 'Business') {
			$category = 'Business & Employment Opportunities';
		}

		$monthyear = $_REQUEST['monthyear'];
		if($monthyear) {
		    $monthyear = date('Y-m',strtotime($monthyear));
		    //$monthyear = $monthyear.'-01';
		} else {
		    $monthyear = "";
		}

		$where = " where";
		$var1 = "";
		$var2 = "";
		$var3 = "";
		if(!empty($edition)) { 
			$andedition = "";
			if(!empty($category) || !empty($monthyear)) { 
				$andedition = " AND"; 
			}
			$var1 = " editions LIKE '%".$edition."%'".$andedition."";
		} 
		if(!empty($category)) { 
			$andcategory = "";
			if(!empty($monthyear)) { 
				$andcategory = " AND"; 
			}
			$var2 = " category LIKE '%".$category."'".$andcategory."";
		}
		if(!empty($monthyear)) { 
					
			//$var3 = " ADVERTISE_MONTH LIKE '%".$searchmonth."%' and (DATE_FORMAT(createdate,'%Y-%m')) <=  '".$monthyear."' and '".$monthyear."' >=   (DATE_FORMAT(createdate,'%Y-%m')) ";
			$var3 = " ADVERTISE_MONTH LIKE '%".$monthyear."%' ";
		}

		//$var3 = " '".$monthyear."' >= (DATE_FORMAT(createdate,'%Y-%m')) AND '".$monthyear."' <= DATE_FORMAT((DATE_FORMAT(createdate,'%Y-%m-%d') + INTERVAL no_of_months MONTH),'%Y-%m')";

		$data = $wpdb->get_results("SELECT *,(DATE_FORMAT(createdate,'%Y-%m-%d') + INTERVAL no_of_months MONTH) AS lastdate FROM wp_ewmw5yx03z_bnoc ".$where.$var1.$var2.$var3." order by createdate desc");
	} else {
		$data = $wpdb->get_results("SELECT *,(DATE_FORMAT(createdate,'%Y-%m-%d') + INTERVAL no_of_months MONTH) AS lastdate FROM wp_ewmw5yx03z_bnoc order by createdate desc");
	}

	foreach($data as $row) {

		$varedition = "";
		if(!empty($edition)) {
			$varedition = $edition;
		} else {
			$varedition = $row->editions;	
		}

		// Display name of months
		$startdate = (new DateTime($row->createddate))->modify('first day of this month');
		$enddate = (new DateTime($row->lastdate))->modify('first day of next month');
		$interval = DateInterval::createFromDateString('1 month');
		$period   = new DatePeriod($startdate, $interval, $enddate);
/*		
		$nameofmonths = array();
		$nameofmonths[0] = date("M", mktime(0, 0, 0, $row->months, 10));
		for ($i = 0; $i<($row->no_of_months-1); ++$i) {
		    $nameofmonths[$i+1] = date("M", mktime(0, 0, 0, $row->months+$i+1, 10));
		}
		$nameofmonths = implode(",",$nameofmonths);*/


		$period =  explode(",",$row->advertise_month);
		$nameofmonths = array();
		foreach ($period as $dt) {
			$selectmonts = explode("-",$dt);
		    $nameofmonths[] =  date("M", mktime(0, 0, 0, $selectmonts[1], 10))."-".$selectmonts[0];
		}
		$nameofmonths = implode(",",$nameofmonths);

		$selmonths = [];
		if( $row->months != 0 ){
			$selectmonts = explode(",",$row->months);

			foreach ($selectmonts as $dt) {									
			    $selmonths[] = date("M", mktime(0, 0, 0, $dt, 10));
			}
			$selmonths = implode(",",$selmonths);
		}
		$status = ucfirst($row->status);
		$rows = array(
			$row->bnoc_ID,
			$row->contactname,
			$row->email,
			$row->phone,
			$varedition,			
			$row->category,
			$row->adtext,
			$row->payment,
			$row->no_of_months,
			$selmonths,
			$nameofmonths,
			$row->createdate,
			$status,
			$row->submit_url
		);
		fputcsv($output, $rows);
	}
	fclose($output);
	exit();
}
add_action('wp_ajax_beacon_export_advertisement', 'beacon_export_advertisement');
add_action('wp_ajax_nopriv_beacon_export_advertisement', 'beacon_export_advertisement');


//demo cron job for mail
function beacon_send_mail_demo() {
	$msg = "First line of text\nSecond line of text";

	// use wordwrap() if lines are longer than 70 characters
	$msg = wordwrap($msg,70);

	// send email
	wp_mail("emailtesterthree@gmail.com","My subject",$msg);
	exit();
}
add_action('wp_cron_beacon_send_mail_demo', 'beacon_send_mail_demo');


//status update for advertisement user
function beacon_status_update_advertisement_user() {

	global $wpdb;

	$data = $wpdb->get_results("UPDATE wp_ewmw5yx03z_bnoc SET status = 'deactive' where SUBSTRING_INDEX(advertise_month,',',-1) = DATE_FORMAT(NOW(),'%Y-%m') and DATE_FORMAT(createdate,'%d') = DATE_FORMAT( (NOW() + INTERVAL 15 DAY),'%d')");
	exit();

}
add_action('wp_cron_beacon_user_status_update', 'beacon_status_update_advertisement_user');


// Advertisement - Send mail advertisement user cron job
function beacon_send_mail_advertisement_user() {
	global $wpdb;

	$data = $wpdb->get_results("SELECT * FROM wp_ewmw5yx03z_bnoc where SUBSTRING_INDEX(advertise_month,',',-1) = DATE_FORMAT(NOW(),'%Y-%m') and DATE_FORMAT(createdate,'%d') = DATE_FORMAT( (NOW() + INTERVAL 5 DAY),'%d') order by createdate desc");

	$emailtext = get_field('email_text','option');
	$admin_email = get_bloginfo('admin_email');
	foreach ($data as $rows) {
		//if($rows->fifteendaysago == $rows->todaydate) {
			$day = date('d', strtotime($rows->createdate));
			$period =  explode(",",$rows->advertise_month);
			$exp = end($period)."-".$day;
			$expiredate = date('m/Y', strtotime(end($period)));
			
			$email = $rows->email;
			$subject = 'Your Advertisement Expired Date - '.$exp;

			// Replace string
			$emailtext  = $emailtext;
			$findtext = ["{contactname}", "{expiredate}", "{editions}", "{category}", "{adtext}"];
			$replacetext   = [$rows->contactname, $expiredate, $rows->editions, $rows->category, $rows->adtext];
			$emailbody = str_replace($findtext, $replacetext, $emailtext);

			$body = "<html><body>".$emailbody."</body></html>";
			/*$body = "<html><body>
			<p>Hello ".$rows->contactname.",</p>
			<p>Your classified ad below is about to expire. Its last appearance is going to be ".$expiredate.". Please go to <a href='".$form_link ."' target='_blank'>".$form_link ."</a> to resubmit your ad and keep it running. Note: you may copy and paste your ad below directly into the box. Feel free to make changes.</p>
			<p>Edition(s): ".$rows->editions."</p> 
			<p>Category: ".$rows->category."</p>
			<p>Ad Text: ".$rows->adtext."</p>  
			<p>Thank you,<br />The Beacon Classified Dept.</p>
			</body></html>";*/
			$headers= 'Content-Type: text/html; charset=UTF-8';
			$headers.= "From:".$admin_email;

			wp_mail( $email, $subject, $body, $headers );
		//}
	}
	exit();
}
add_action('wp_cron_beacon_send_mail_advertisement_user', 'beacon_send_mail_advertisement_user');


add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');
function new_mail_from($old) {
	$admin_email = get_bloginfo('admin_email');
	return $admin_email;
}
function new_mail_from_name($old) {
	$emailname = get_field('email_name','option');
	return $emailname;
}
/* End custom gravity forms */


/**
 * Extend WordPress search to include custom fields
 */

/**
 * Join posts and postmeta tables
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_join
 */
function cf_search_join( $join ) {
    global $wpdb;

    if ( is_search() ) {    
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }

    return $join;
}
add_filter('posts_join', 'cf_search_join' );

/**
 * Modify the search query with posts_where
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_where
 */
function cf_search_where( $where ) {
    global $pagenow, $wpdb;

    if ( is_search() ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    	$where .= " AND ".$wpdb->posts.".ID NOT IN (
               SELECT pm.post_id FROM ".$wpdb->postmeta." pm
              INNER JOIN ".$wpdb->postmeta." pm2 ON pm2.post_id = pm.post_id
               WHERE pm.`meta_key` = 'events_to_date' AND pm2.`meta_key` = 'events_to_time' AND STR_TO_DATE(pm.meta_value,'%Y%m%d') <= DATE(NOW())
                )";
    /*if($_SERVER['REMOTE_ADDR'] == '103.251.217.63'){
		echo $where;
		exit;
	}*/

    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );

/**
 * Prevent duplicates
 *
 * http://codex.wordpress.org/Plugin_API/Filter_Reference/posts_distinct
 */
function cf_search_distinct( $where ) {
    global $wpdb;

    if ( is_search() ) {
        return "DISTINCT";
    }

    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );


function get_distribution_list($latitude,$longitude,$distance=0){
	global $wpdb,$post;
	if (!empty($latitude) || !empty($longitude)) {	
	$sql = "SELECT DISTINCT {$wpdb->prefix}location_latlong.post_id,{$wpdb->prefix}postmeta.meta_value,  Min( 3959 * ACOS( COS( RADIANS( $latitude ) ) * COS( RADIANS( location_latitude ) ) 
		* COS( RADIANS( location_longitude ) - RADIANS( $longitude ) ) + SIN( RADIANS( $latitude ) ) * SIN( RADIANS( location_latitude ) ) ) ) AS distance
		FROM {$wpdb->prefix}location_latlong";
	}
	$where = " WHERE posts.post_type = 'distribution' AND posts.post_status = 'publish' AND ( {$wpdb->prefix}postmeta.meta_key = 'zip_code') AND pm.meta_key = 'public' AND pm.meta_value = 'yes' ";
	$join = " INNER JOIN {$wpdb->prefix}posts AS posts ON ( posts.ID = {$wpdb->prefix}location_latlong.post_id ) LEFT JOIN {$wpdb->prefix}postmeta ON posts.ID = {$wpdb->prefix}postmeta.post_id LEFT JOIN {$wpdb->prefix}postmeta pm ON posts.ID = pm.post_id";
	$sql .= $join;
	$sql .= $where;
	$sql .= " GROUP BY posts.ID";
	if(!empty($distance) && $distance != 0){
		$sql .= " HAVING distance < ".$distance;	
	}
	$sql .= " ORDER BY distance ASC";
	/*echo $sql;
	exit;*/
	
	$result = $wpdb->get_results($sql,ARRAY_A);	
	return $result;
}

function get_the_user_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters( 'wpb_get_ip', $ip );
}
add_shortcode('show_ip', 'get_the_user_ip');

add_action('wp_head', 'add_google_analytics');
function add_google_analytics() { ?>
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-15582367-6"></script>
	<script>
	  window.dataLayer = window.dataLayer || [];
	  function gtag(){dataLayer.push(arguments);}
	  gtag('js', new Date());

	  gtag('config', 'UA-15582367-6', {
		'custom_map': {
			'dimension1' : 'ip',
			'dimension2' : 'postId',
			'dimension3' : 'typeName',
			'dimension4' : 'link',
			'dimension5' : 'type'
		}
	  });
	  gtag('event', 'ip_address', {
		  'ip': '<?php echo get_the_user_ip();?>',
		  'typeName' : 'Visit Website',
		  'postId' : <?php echo get_queried_object_id(); ?>,
		  'link': '<?php echo get_permalink(get_queried_object_id()); ?>',
		  'type' : 'website'
	  });
	  <?php if (is_singular('silverpage')) : ?>
	    gtag('event', 'ip_address', {
		  'ip': '<?php echo get_the_user_ip();?>',
		  'typeName' : 'Visit SilverPage',
		  'postId' : <?php echo get_queried_object_id(); ?>,
		  'link': '<?php echo get_permalink(get_queried_object_id()); ?>',
		  'type' : 'silverpage'
	    });
	  <?php endif; ?>
	  function gaClickTrack(send, event, type, typeName, postId, link) {
		gtag('event', 'ip_address', {'ip': '<?php echo get_the_user_ip();?>', 'typeName' : typeName, 'postId' : postId, 'link': link , 'type' : type});
		console.log(" send: "+ send +" event: "+ event +" type: "+ type +" typeName: "+ typeName + " postId: "+ postId + " link: "+ link);
	  }
	</script>
<?php
}

// add_action( 'init', 'issuu_folder_update' );
 
function issuu_folder_update() {

	$plugin_dir = ABSPATH . 'wp-content/plugins/issuu/issuuApi.php';
	include_once $plugin_dir;
	
	$client = new IssuApi();
	
	// Print List of Folder
	// echo '<pre>', print_r($client->getFolderList(0, 10)) ,'</pre>';
	
	$folder_list = $client->getFolderList(0, 10);
	
	$folderId = array();
	$folderName = array();
	
	$folder_structure = $folder_list['rsp']['_content']['result']['_content'];
	
	foreach ($folder_structure as $key => $value) {
		
		$folder_id = $value['folder']['folderId'];
		$folder_name = $value['folder']['name'];

		$term = term_exists( $folder_name , 'issuu_folder' );

		if ( $term == 0 && $term == null ) {

			$id =  wp_insert_term($folder_name ,'issuu_folder');

			$post_id = $id['term_taxonomy_id'];
	
			update_field( 'folder_id', $folder_id , 'issuu_folder_'.$post_id );
		}
	
	}

}

// add_action( 'init', 'add_embed_document' );

function add_embed_document() {

		$plugin_dir = ABSPATH . 'wp-content/plugins/issuu/issuuApi.php';
		include_once $plugin_dir;

		$client = new IssuApi();

		function division($integer_num){
			return intval($integer_num / 100);
		}

		/**
		 * 
		 * Get list of all document Id of document list.
		 * 
		 */ 

		$document_list_array = array();
		$document_total_count = $client->getDocumentList()['rsp']['_content']['result']['totalCount'];

		$document_iteration = division($document_total_count);

		for($i = 0; $i <= ($document_iteration * 100); $i = $i + 100 )
		{
			foreach($client->getDocumentList($i, 100)['rsp']['_content']['result']['_content'] as $key => $value) 
			{
				array_push($document_list_array ,$value['document']['documentId']);
			}
		}

		// echo 'Document'.'<pre>',print_r($document_list_array),'</pre>';

		/**
		 * 
		 * Get list of all document id of document embed list.
		 * 
		 */ 

		$document_embed_list_array = array();
		$document_embed_total_count = $client->getEmbedsList()['rsp']['_content']['result']['totalCount'];

		$document_embed_iteration  = division($document_embed_total_count);


		for($i = 0; $i <= ($document_embed_iteration * 100); $i = $i + 100 )
		{
			foreach($client->getEmbedsList($i, 100)['rsp']['_content']['result']['_content'] as $key => $value) 
			{
				if(!empty($value['documentEmbed']['documentId'])){
					array_push($document_embed_list_array ,$value['documentEmbed']['documentId']);
				}
			}
		}

		// echo 'Document Embed List'.'<pre>',print_r($document_embed_list_array),'</pre>';


		/**
		 * Filter out array which does not match both document and document embed list
		 *
		 */ 

		$final_result = array_diff($document_list_array , $document_embed_list_array);

		/**
		 * 
		 * Filter out array which needed to be added in add embed api.
		 * 
		 */ 

		$array_intersect = array_intersect($document_list_array , $final_result);

		/***
		 * 
		 * Create Embed List 
		 */ 

		foreach($array_intersect as $value){
			$client->createDocumentEmbed( $value );
		}
}



// add_action( 'init', 'issuu_magazine' );

function issuu_magazine() {


		$plugin_dir = ABSPATH . 'wp-content/plugins/issuu/issuuApi.php';
		include_once $plugin_dir;
		
		$client = new IssuApi();

		function division($integer_num)
		{
			return intval($integer_num / 100);
		}

		/**
		 * 
		 * Get list of all document Id of document list.
		 * 
		 */

		$document_list_array = array();
		$document_total_count = $client->getDocumentList()['rsp']['_content']['result']['totalCount'];

		$document_iteration = division($document_total_count);

		for ($i = 0; $i <= ($document_iteration * 100); $i = $i + 100) {
			foreach ($client->getDocumentList($i, 100)['rsp']['_content']['result']['_content'] as $key => $value) {
				// array_push($document_list_array ,$value['document']['documentId']);

				$document_array = [
					"documentId"    => $value['document']['documentId'],
					"title"         => $value['document']['title'],
					"description"   => isset($value['document']['description']) ? $value['document']['description'] : '',
					'folders'       => isset($value['document']['folders'][0]) ? $value['document']['folders'][0] : 'uncategorized'
				];
				// echo '<pre>', print_r($document_array) ,'</pre>';
				array_push($document_list_array , $document_array);
			}
		}

		/**
		 * 
		 * Get list of all document id of document embed list.
		 * 
		 */

		$document_embed_list_array = array();
		$document_embed_total_count = $client->getEmbedsList()['rsp']['_content']['result']['totalCount'];

		$document_embed_iteration  = division($document_embed_total_count);


		for ($i = 0; $i <= ($document_embed_iteration * 100); $i = $i + 100) {
			foreach ($client->getEmbedsList($i, 100)['rsp']['_content']['result']['_content'] as $key => $value) {
				if (!empty($value['documentEmbed']['documentId'])) {
					$document_embed_array = [
						"documentId"    => $value['documentEmbed']['documentId'],
						"id"            => $value['documentEmbed']['id'],
						// "embed_doc"     => $client->getEmbedHtml(85974456)
					];
					array_push($document_embed_list_array, $document_embed_array);
				}
				
			}
		}

		$merge_array = array();

		foreach ($document_list_array as $key => &$value_1) {
			// if($value['name'])
			foreach ($document_embed_list_array as $key => $value_2) {
				if (!empty($value_2['documentId'])) {
					if($value_1['documentId'] === $value_2['documentId']) {
						$merge_array[] = array_merge($value_1,$value_2);
					}
				}
			}
		}

		// echo 'Merge array <pre>', print_r($merge_array) ,'</pre>';

		foreach ($merge_array as $value) {
			$post_id = array(
				'post_type' 	=> 'issuu-magazines',
				'post_title'    => $value['title'],
				'post_content'  => $value['description'],
				'post_status'   => 'publish',
				'post_author'   => 1,
				'tax_input'    => array(
					'issuu_folder'     => $value['folders'],
				)
			);

			$id = wp_insert_post( $post_id );

			update_field( 'embed_id', $value['id'] , $id );
			update_field( 'embed_doc', $client->getEmbedHtml($value['id']) , $id );

			// add_post_meta($post_id, 'embed_doc', $client->getEmbedHtml($value['id']));
		}


}

