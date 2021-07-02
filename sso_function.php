<?php


if ((isset($_REQUEST['page']) == 'export-locations') && is_admin()) {
    ob_start();
}
add_action('admin_menu', 'my_users_menu');

function my_users_menu() {
    add_users_page('Export Locations', 'Export Locations', 'read', 'export-locations', 'export_locations');
}

function export_locations() {
    global $wpdb;
    ob_end_clean();
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=pending_pledgers_report_" . date('Y-m-d') . ".csv");
    header("Pragma: no-cache");
    header("Expires: 0");

    /* # of Pledge */
    $qry = "SELECT * FROM content WHERE  folder_id = 172";
    $results = $wpdb->get_results($qry);
    $insert_post_count = 0;
    $dataarray = array();
    if (!empty($results)) {
        $key = 0;
        foreach ($results as $result) {
            $content_title = $result->content_title;
            $content = $result->content_html;
            if (!empty($content)) {
                $xml = simplexml_load_string($content);

                $description = $xml->Description;
                $StartTime = $xml->StartTime;
                $EndTime = $xml->EndTime;
                $Duration = $xml->Duration;
                $DisplayTitle = $xml->DisplayTitle;
                $Location = $xml->Location;
                $IsAllDay = $xml->IsAllDay;
                $IsVariance = $xml->IsVariance;
                $IsCancelled = $xml->IsCancelled;

                $IsAllDay = $xml->IsAllDay;

                $Location = $xml->Location;

                $location_fields = explode(', ', $Location);

                $location_title = $location_fields[0];
                $location_city = $location_fields[1];
                $location_state = $location_fields[2];
                if (!empty($location_title)) {
                    $dataarray[$key]['post_title'] = $content_title;
                    $dataarray[$key]['post_content'] = $description;
                    $dataarray[$key]['post_excerpt'] = "";
                    $dataarray[$key]['post_date'] = '';
                    $dataarray[$key]['event_category'] = '';
                    $dataarray[$key]['event_tag'] = '';
                    $dataarray[$key]['post_author'] = '';
                    $dataarray[$key]['featured_image'] = '';
                    $dataarray[$key]['post_slug'] = '';
                    $dataarray[$key]['post_parent'] = '';
                    $dataarray[$key]['post_status'] = 'publish';
                    $dataarray[$key]['location_name'] = $location_title;
                    $dataarray[$key]['location_address'] = $location_title;
                    $dataarray[$key]['location_town'] = $location_city;
                    $dataarray[$key]['location_state'] = $location_state;
                    $dataarray[$key]['location_postcode'] = '';
                    $dataarray[$key]['location_region'] = '';
                    $dataarray[$key]['location_country'] = 'US';
                    $dataarray[$key]['event_start_date'] = $event_start_date;
                }
            }
            $key++;
        }
        //$dataarray = array_unique($dataarray);
        $loc_title = array();
        foreach ($dataarray as $key => $val) {
            //foreach($val as $v){
            if (in_array($val['post_title'], $loc_title)) {
                unset($dataarray[$key]);
            } else {
                $loc_title[] = $val['post_title'];
            }
            //}			
        }
        $file = fopen('php://output', 'w');
        fputcsv($file, array(
            'post_title',
            'post_content',
            'post_excerpt',
            'post_date',
            'post_author',
            'featured_image',
            'location_address',
            'location_town',
            'location_state',
            'location_postcode',
            'location_region',
            'location_country',
        ));
        foreach ($dataarray as $row) {
            fputcsv($file, $row);
        }

        /* $file = fopen('php://output', 'w');
          fputcsv($file, array('Donor Name', 'Donor Email', 'Goalie Name for Pledge', 'Donor Phone Number','Donor Pledge Amount','Total Goalie Saves', 'Total Amount Due'));
          foreach($dataarray as $row){
          fputcsv($file, $row);
          } */
        exit();
    }
}

remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'wp_generator');

/* all image size listed here */

//add_image_size('middle_content',1055,655,array('center,center'));

/**
 * Remove Word press Version for securities purpose.
 */
function acte_remove_version() {
    return '';
}

add_filter('the_generator', 'acte_remove_version');

// Disable support for comments and trackbacks in post types
function acte_disable_comments_post_types_support() {
    $post_types = get_post_types();
    foreach ($post_types as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
}

//add_action('admin_init', 'acte_disable_comments_post_types_support');
// Close comments on the front-end
function acte_disable_comments_status() {
    return false;
}

//add_filter('comments_open', 'acte_disable_comments_status', 20, 2);
//add_filter('pings_open', 'acte_disable_comments_status', 20, 2);
// Hide existing comments
function acte_disable_comments_hide_existing_comments($comments) {
    $comments = array();
    return $comments;
}

//add_filter('comments_array', 'acte_disable_comments_hide_existing_comments', 10, 2);
// Remove comments page in menu
function acte_disable_comments_admin_menu() {
    remove_menu_page('edit-comments.php');
}

//add_action('admin_menu', 'acte_disable_comments_admin_menu');
// Redirect any user trying to access comments page
function acte_disable_comments_admin_menu_redirect() {
    global $pagenow;
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
}

//add_action('admin_init', 'acte_disable_comments_admin_menu_redirect');
// Remove comments metabox from dashboard
function acte_disable_comments_dashboard() {
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}

//add_action('admin_init', 'acte_disable_comments_dashboard');
// Remove comments links from admin bar
function acte_disable_comments_admin_bar() {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
}

//add_action('init', 'acte_disable_comments_admin_bar');

function remove_cssjs_ver($src) {
    if (strpos($src, '?ver='))
        $src = remove_query_arg('ver', $src);
    return $src;
}

if (!is_admin()) {
    add_filter('style_loader_src', 'remove_cssjs_ver', 10, 2);
    add_filter('script_loader_src', 'remove_cssjs_ver', 10, 2);
}



if (!function_exists('acte_link_fillter')):

// Don't use href when you using this function
//echo '<a '.acte_link_fillter($link, true).'>View Album</a>';
    function acte_link_fillter($link = null, $target = null) {
        $href_link = null;
        // For external link condition
        if (!empty($link) && $link != null) {
            if ($link == '#') {
                $href_link = $link;
                $target = '';
            } else {
                $url = trim($link);
                if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
                    $href_link = "http://" . $url;
                } else {
                    $href_link = trim($link);
                }
            }
        }
        // For target condition
        if ($target == true) {
            return 'href="' . $href_link . '" target="_blank"';
        } else {
            return 'href="' . $href_link . '"';
        }
    }

endif;

function acte_limit_text($string, $limit) {
    if (!empty($string)) {
        $string = strip_tags($string);
        if (strlen($string) > $limit) {
            $stringCut = substr($string, 0, $limit);
            $string = substr($stringCut, 0, strrpos($stringCut, ' '));
        }
        return $string;
    } else {
        return false;
    }
}

function remove_empty_p($content) {
    $content = force_balance_tags($content);
    $content = preg_replace('#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content);
    $content = preg_replace('~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content);
    return $content;
}

//add_filter('the_content', 'remove_empty_p', 99, 1);
//add_filter( 'admin_post_thumbnail_html', 'add_featured_image_instruction');
function add_featured_image_instruction($content) {
    global $post;
    $post_type = get_post_type();
    if ($post_type == 'post') {
        $content = '<p>Please upload 285 * 285 image size only </p>' . $content;
    }
    return $content;
}

/*
 * Add your own functions here. You can also copy some of the theme functions into this file. 
 * Wordpress will use those functions instead of the original functions then.
 */

add_theme_support('avia_template_builder_custom_css');


add_action('wp_enqueue_scripts', 'acte_styles', 20);

function acte_styles() {
    wp_enqueue_style('custom-css', get_stylesheet_directory_uri() . '/css/custom.css');
    wp_enqueue_style('media-css', get_stylesheet_directory_uri() . '/css/responsive.css');
    wp_enqueue_style('shortcode-css', get_stylesheet_directory_uri() . '/css/shortcode.css');
}

function acte_scripts() {
    // CSS
    wp_enqueue_style('owl-carousel-css', get_stylesheet_directory_uri() . '/css/owl.carousel.min.css');
    // JS
    wp_enqueue_script('owl-carousel-js', get_stylesheet_directory_uri() . '/js/owl.carousel.min.js', array('jquery'), '20151215', true);
    wp_enqueue_script('custom-script', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '20151215', true);

    // wp_enqueue_script('custom-slider-script', get_stylesheet_directory_uri().'/js/custom_slider.js', array('jquery'), '20151215', true);
    //  wp_enqueue_script('wowslider-script', get_stylesheet_directory_uri().'/js/wowslider.js', array('jquery'), '20151215', true);
     wp_enqueue_script('shortcode-js', get_stylesheet_directory_uri() . '/js/shortcode.js', array('jquery'), '20151215', true);
    wp_localize_script('custom-script', 'acte_admin_URL_Name', acte_admin_URL());
}

add_action('wp_enqueue_scripts', 'acte_scripts');

function wp_change_aviajs() {
	if ( ! is_admin() ) {
		wp_dequeue_script( 'avia-default' );
		wp_enqueue_script( 'avia-default-child', get_stylesheet_directory_uri().'/js/avia.js', array('jquery'), 2, true );
	}
}
add_action( 'wp_print_scripts', 'wp_change_aviajs', 100 );

function acte_admin_URL() {
    $MyTemplatepath = get_template_directory_uri();
    $MyHomepath = esc_url(home_url('/'));
    $admin_URL = admin_url('admin-ajax.php'); // Your File Path
    return array(
        'admin_URL' => $admin_URL,
        'MyTemplatepath' => $MyTemplatepath,
        'MyHomepath' => $MyHomepath,
    );
}

// Include Custom post type
//include("includes/type-regions.php");
//Include Sate Post
include("includes/type-state.php");

// Include Region Sub pages Post
include("includes/type-region_pages.php");

//include("includes/type-publication.php");


/* include techniques post type */
include("type/techniques.php");

/* include User Section File */
include("includes/user-section.php");

/* Include Press Center Custom Post Type */
include("includes/type-press_center.php");

/* Include Press Hits Custom Post type */
include("includes/type-press_hits.php");

/* Include Press Hits Custom Post type */
include("includes/type-home_page_box.php");

include("includes/type-state_fact_sheets.php");


/* Excerpt length i.e limiting your "blog post" content in avia editor */

function avia_new_excerpt_length($length) {
    if (is_archive('blog')) {
        return 14;
    } else {
        return 25;
    }
}

add_filter('excerpt_length', 'avia_new_excerpt_length');
/* Excerpt length i.e limiting your "blog post" content in avia editor */
/* Removed [...] at the end of excerpt and added ... */

function avia_excerpt_more_change($more) {
    return '...';
}

add_filter('excerpt_more', 'avia_excerpt_more_change');
/* Removed [...] at the end of excerpt and added ... */


/*  Overwrites Footer Columns Settings */
add_filter('avf_option_page_data_init', 'my_avf_option_page_data_add_elements', 10, 1);

function my_avf_option_page_data_add_elements(array $avia_elements = array()) {
   foreach ($avia_elements as $key => $avia_elements_value) {

       if (isset($avia_elements_value['id']) && $avia_elements_value['id'] == 'footer_columns') {

           $avia_elements[$key] = array(
               "slug" => "footer",
               "name" => __("Footer Columns", 'avia_framework'),
               "desc" => __("How many columns should be displayed in your footer", 'avia_framework'),
               "id" => "footer_columns",
               "type" => "select",
               "std" => "4",
               "subtype" => array(
                   __('1', 'avia_framework') => '1',
                   __('2', 'avia_framework') => '2',
                   __('3', 'avia_framework') => '3',
                   __('4', 'avia_framework') => '4',
                   __('5', 'avia_framework') => '5',
                   __('6', 'avia_framework') => '6',
                   __('7', 'avia_framework') => '7'));
       }
   }
   return $avia_elements;
}

// Custom Image Sizes
add_image_size('home_accordion_slider', 535, 595, true);
add_image_size('partner_image', 250, 250, true);
add_image_size('advertisement_image', 362, 362, true);
add_image_size('banner_image', 1920, 550, true);
//add_image_size('banner_image', 1920, 710, true);
add_image_size('beacon_flipbook_small', 170, 227, true);

// Add Custom Image Size for Banner in Enfold
add_image_size('banner-with-text', 1920, 565, true);
add_image_size('banner-without-text', 1920, 550, true);
add_filter('image_size_names_choose', 'acte_custom_image_sizes');

function acte_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'banner-with-text' => __('Banner With Text'),
        'banner-without-text' => __('Banner Without Text'),
    ));
}

// Enfold's builder available on the individual session post types /

add_filter('avf_builder_boxes', 'add_builder_to_posttype');

function add_builder_to_posttype($metabox) {
    foreach ($metabox as &$meta) {
        if ($meta['id'] == 'avia_builder' || $meta['id'] == 'layout') {
            $meta['page'][] = 'regions';
        }
    }

    return $metabox;
}

function list_regions() {

    $return_html = '';
    if (!empty($_GET['category'])) {
        $filter_category = $_GET['category'];
        $filterSlug = (!empty($filter_category)) ? $filter_category : '';
    }
    $metaQuery = '';
    if (!empty($_GET['unified'])) {

        $filter_unified = $_GET['unified'];

        if ($filter_unified == 'unified') {
            $filterUni = 1;
        } else if ($filter_unified == 'non-unified') {
            $filterUni = 0;
        } else {
            $filterUni = '';
        }

        $metaQuery = array(
            array(
                'key' => 'is_unified',
                'value' => $filterUni,
                'compare' => '=',
            )
        );
    }

    // Category Filter
    $taxonomy = 'state_region';
    $term_args = array(
        'orderby' => 'name',
        'order' => 'ASC'
    );
    $terms = get_terms($taxonomy, $term_args);
    $return_html = '<form method="GET" name="filter">';
    $return_html .= '<div class="region-search row">';
    if (!empty($terms)) {
        $return_html .= '<div class="col-sm-4 select">';
        $return_html .= '<select name="category" >';
        $return_html .= '<option data-hidden="true" value="">REGION - ALL</option>';
        $i = 1;
        foreach ($terms as $category) {
            $cat_name = $category->name;
            $cat_slug = $category->slug;
            $term_id = $category->term_id;
            $return_html .= '<option value="' . $cat_slug . '" ' . (( $cat_slug == $filter_category ) ? 'selected' : '') . ' >' . $cat_name . '</option>';
            $i++;
        }
        $return_html .= '</select>';
        $return_html .= '</div>';
    }
    $return_html .= '<div class="col-sm-4 select">';
    $return_html .= '<select name="unified">';
    $return_html .= '<option data-hidden="true" value="">AFFILIATION - ALL</option>';
    $return_html .= '<option value="unified" ' . (($filter_unified == "unified") ? 'selected' : '') . '>UNIFIED</option>';
    $return_html .= '<option value="non-unified" ' . (($filter_unified == "non-unified") ? 'selected' : '') . '>NON-UNIFIED</option>';
    $return_html .= '</select>';
    $return_html .= '</div>';
    $return_html .= '<div class="col-sm-4 region-search-btn">';
    $return_html .= '<input type="submit" value="SEARCH" />';
    $return_html .= '</div>';
    $return_html .= '</div>';
    $return_html .= '</form>';

    $term_args = array(
        'orderby' => 'name',
        'order' => 'ASC',
        'slug' => $filterSlug
    );
    $terms = get_terms($taxonomy, $term_args);


    if (!empty($terms)) {
        $return_html .= '<div class="region-result">';
        foreach ($terms as $term) {

            $regionsArgs = array(
                'post_type' => 'states',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'state_region',
                        'field' => 'term_id',
                        'terms' => $term->term_id,
                    )
                ),
                'meta_query' => $metaQuery
            );
            $regions = new WP_Query($regionsArgs);


            if ($regions->have_posts()) :

                $return_html .= '<h2 class="search-title"><a href="' . get_term_link($term->term_id) . '">' . $term->name . '</a></h2>';
                $return_html .= '<div class="region-list">';
                while ($regions->have_posts()) : $regions->the_post();
                    $region_ID = get_the_ID();
                    $is_unified = get_field('is_unified', $region_ID);
                    $select_state_page = get_field('state', $region_ID);

                    // if(!empty($select_state_page)){
                    if ($select_state_page == 'link') {
                        $page_link = get_field('state_link', $region_ID);
                        $target = "_blank";
                        $page_link = !empty($page_link) ? $page_link : "javascript:void(0)";
                    } else if ($select_state_page == 'page') {
                        $page_link = get_field('state_page', $region_ID);
                        $target = " ";
                        $page_link = !empty($page_link) ? $page_link : "javascript:void(0)";
                    } else {
                        $target = " ";
                        $page_link = "#";
                    }
                    // }

                    $class = (!$is_unified) ? 'non' : '';
                    $unifiedText = (!$is_unified) ? 'NON-UNIFIED' : 'UNIFIED';


                    $return_html .= '<div class="region-list-row">';
                    $return_html .= '<div class="region-list-cell region-name col-sm-3">' . get_the_title() . '</div>';
                    $return_html .= '<div class="region-list-cell region-status ' . $class . ' col-sm-6">' . $unifiedText . '</div>';
                    $return_html .= '<div class="region-list-cell region-link col-sm-3"><a href="' . $page_link . '" target="' . $target . '">VIEW STATE PAGE</a></div>';
                    $return_html .= '</div>';

                endwhile;
                wp_reset_query();
                $return_html .= '</div>';

            endif;
        }
        $return_html .= '</div>';
    }

    return $return_html;
}

/* --- Map Listing ----- */

function region_map_listing() {

    $map_image = get_field('upload_map_image', 'option');
    $taxonomy = 'state_region';
    $term_args = array(
        'orderby' => 'name',
        'order' => 'ASC'
    );
    $terms = get_terms($taxonomy, $term_args);
    $return_html = '<div style="width:100%;">';
    $return_html .= '<img id="img_ID" src="' . $map_image['url'] . '" usemap="#map" border="0" width="100%" alt="" />';
    $return_html .= '</div>';
    $return_html .= '<map name="map" id="map_ID">';
    if (!empty($terms)) {
        foreach ($terms as $term) {
            $region_area_co_ordinates = get_field('region_area_co_ordinates', 'state_region' . _ . $term->term_id);
            $region_link = get_term_link($term->term_id);
            $region_name = $term->name;
            $return_html .= '<area title="' . $region_name . '" alt="' . $region_name . '" coords="' . $region_area_co_ordinates . '" shape="poly" href="' . $region_link . '" target="_blank" />';
        }
    }
    $return_html .= '</map>';
    return $return_html;
}

/* ---- End of Map Listing ---- */
/* ---------------------- State Association Page Map Listing ----------------------- */

function list_state_map() {
    $map_image = get_field('upload_map_image', 'option');
    $taxonomy = 'state_region';
    $term_args = array(
        'orderby' => 'name',
        'order' => 'ASC'
    );
    $terms = get_terms($taxonomy, $term_args);
    $return_html = '<div style="width:100%;">';
    $return_html .= '<img id="img_ID" src="' . $map_image['url'] . '" usemap="#map" border="0" width="100%" alt="" />';
    $return_html .= '</div>';
    $return_html .= '<map name="map" id="map_ID">';
    if (!empty($terms)) {
        foreach ($terms as $term) {

            $regionsArgs = array(
                'post_type' => 'states',
                'posts_per_page' => -1,
                'post_status' => 'publish',
                'tax_query' => array(
                    array(
                        'taxonomy' => 'state_region',
                        'field' => 'term_id',
                        'terms' => $term->term_id,
                    )
                )
            );
            $states = new WP_Query($regionsArgs);
            if ($states->have_posts()) {
                while ($states->have_posts()) {
                    $states->the_post();
                    $state_ID = get_the_ID();
                    $state_name = get_the_title($state_ID);
                    $state_co_ordinates = get_field('map_area_co_ordinates', $state_ID);
                    $state_area_shape = get_field('area_shape', $state_ID);
                    $select_state_page = get_field('state', $state_ID);
                    // if(!empty($select_state_page)){
                    if ($select_state_page == 'link') {
                        $page_link = get_field('state_link', $state_ID);
                        $target = "_blank";
                        $page_link = !empty($page_link) ? $page_link : "javascript:void(0)";
                    } else if ($select_state_page == 'page') {
                        $page_link = get_field('state_page', $state_ID);
                        $target = " ";
                        $page_link = !empty($page_link) ? $page_link : "javascript:void(0)";
                    } else {
                        $target = " ";
                        $page_link = "#";
                    }
                    // }
                    $return_html .= '<area title="' . $state_name . '" alt="' . $state_name . '" coords="' . $state_co_ordinates . '" shape="' . $state_area_shape . '" href="' . $page_link . '" target="' . $target . '" />';
                }wp_reset_query();
            }
        }
    }
    $return_html .= '</map>';
    return $return_html;
}

/* ---------------------- End of State Association Map Listin ---------------------- */

function site_shortcodes_init() {
    if (is_admin()) {
        return;
    }
    add_shortcode('list_regions', 'list_regions');
    add_shortcode('region_map_listing', 'region_map_listing');
    add_shortcode('list_state_map', 'list_state_map');
}

add_action('init', 'site_shortcodes_init', 11);


// LOGIN LOGOUT MENU
add_filter('wp_nav_menu_items', 'wti_loginout_menu_link', 10, 2);

function wti_loginout_menu_link($items, $args) {

    if ($args->theme_location == 'avia2') {
        if (is_user_logged_in()) {
            // $logout_link = 'http://web.acteonline.org/ACTE/SSO_Logout.aspx';
            $logout_link = 'https://acte01.isgazurecloud.com/imis/acte/aslogin';
            
            // $items .= '<li class="right"><a href="' . wp_logout_url($logout) . '">' . __("LOGOUT") . '</a></li>';
            $items .= '<li class="right"><a href="' . wp_logout_url() . '">' . __("LOGOUT") . '</a></li>';



            //if(isset($_SESSION['publication_page_id'])){
            //unset($_SESSION['publication_page_id'],$_SESSION['restricted_page_id']);
            //}else if(isset($_SESSION['restricted_page_id'])){
            //	unset($_SESSION['restricted_page_id']);
            //}
        } else {
            // $items .= '<li class="right"><a href="http://web.acteonline.org/ACTE/SSO_Login.aspx">' . __("LOGIN") . '</a></li>';
               $items .= '<li class="right"><a href="https://acte01.isgazurecloud.com/imis/acte/aslogin">' . __("LOGIN") . '</a></li>';
        }
    }
    return $items;
}

add_action( 'wp_logout', 'auto_redirect_external_after_logout');
function auto_redirect_external_after_logout(){
  $home_url = esc_url(home_url('/'));
  $logout = 'https://acte01.isgazurecloud.com/imis/AsiCommon/Controls/Shared/FormsAuthentication/logout.aspx?returnurl='.$home_url;
  wp_redirect( $logout );
  exit();
}

/* Change wp_logout_url */
/* add_action( 'wp_logout', 'auto_redirect_external_after_logout');
  function auto_redirect_external_after_logout(){
  wp_redirect( 'http://web.acteonline.org/ACTE/SSO_Logout.aspx' );
  exit();
  } */
add_filter('allowed_redirect_hosts', 'allow_ms_parent_redirect');

function allow_ms_parent_redirect($allowed) {
    $allowed[] = 'web.acteonline.org';
    return $allowed;
}

/**
 *  Add Acf options page in admin
 */
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'General Settings',
        'menu_title' => 'General Settings',
        'menu_slug' => 'general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
}

/**
 * Add Short Code for Advertisement block in sidebar widget
 */
function advertise_shortcode() {
    ?>
    <script type="text/javascript">
	  var protocol = document.location.protocol == "https:" ? "https" : "http";
	  var z = document.createElement("script");
	  z.type = "text/javascript";
	  z.src = protocol + "://s.zkcdn.net/ados.js";
	  z.async = true;
	  var s = document.getElementsByTagName("script")[0];
	  s.parentNode.insertBefore(z,s);
	</script>
	<script type="text/javascript">
	  var ados = ados || {};
	  ados.run = ados.run || [];
	  ados.run.push(function() {
		/* load placement for account: Multiview, site: ACTEweb - Association for Career and Technical Education - MultiWeb, size: 308x308 - 308 x 308, zone: ACTEweb - Box Ad - 308x308 */
		ados_add_placement(4466, 64571, "mvBoxAd", 2430).setZone(92092);
	ados_setDomain('engine.multiview.com');
		ados_load();
	  });
	</script>
    <div id="mvBoxAd" style="text-align:center;"></div>
    <?php
}

add_shortcode('advertise_shortcode', 'advertise_shortcode');

/**
 * Add short code for thank you page redirect
 */
add_action('wp_footer', 'mycustom_wp_footer');

function mycustom_wp_footer() {
    $thankyoupage = get_page_by_path('corporate-membership');
    ?>
    <script type="text/javascript">
        document.addEventListener('wpcf7mailsent', function (event) {
            window.location.href = "<?php echo get_the_permalink($thankyoupage); ?>";
        }, false);
    </script>
    <?php
}

add_action('after_setup_theme', function() {
    if (is_child_theme())
        remove_action('tribe_events_template', 'avia_events_tempalte_paths', 10, 2);
});

add_action('tribe_events_template', 'avia_events_template_paths_mod', 10, 2);

function avia_events_template_paths_mod($file, $template) {
    $redirect = array('default-template.php', 'single-event.php');
    if (in_array($template, $redirect)) {
        $file = get_stylesheet_directory() . "/tribe-events/views/" . $template;
    }

    return $file;
}

function events_cat_func($atts) {
    $taxonomy = "tribe_events_cat";
    $tax_terms = get_terms($taxonomy, array('hide_empty' => false));
    $html = "";
    if (!empty($tax_terms)) {
        foreach ($tax_terms as $term_single) {

            $slug = $term_single->slug;
            //$tax_term = $term_single->term_id;  
            $name = $term_single->name;
            $html .= '<div class="list-inline"><a href="' . esc_attr(get_term_link($term_single, $taxonomy)) . '">' . $name . '</a></div>';
        }
    }
    return $html;
}

add_shortcode('events_cat', 'events_cat_func');

/* Archive Page - remove image in category listing function */

function custom_category_excerpt($c) {
    if (is_category()) {
        $content = preg_replace('/(<img [^>]*>)/', '', wp_strip_all_tags(get_the_content()));
        // $content = apply_filters('the_content', $content);				

        return substr($content, 0, 300) . "...";
    }
    return $c;
}

add_filter('the_content', 'custom_category_excerpt', 300, 1);

/* Restricted Page Redirect Function */

function get_id($id) {
    //echo $id;
    my_page_template_redirect($id);
}

function my_page_template_redirect($id) {
    $restricted_page = get_field('is_restricted_page_', $id);
    $login_restricted_pages = get_field('restricted_page', 'option');
    if (!empty($login_restricted_pages)) {
        foreach ($login_restricted_pages as $pages) {
            //$selected_field = $pages['select_page_or_attachment'];
            //if($selected_field == 'Page'){
            $page_link = $pages['add_restricted_pages'];
            $page_id = $page_link->ID;
            $page_link = get_permalink($page_id);
            $page_id_array[] = $page_id;
            //} 
        }
        if (!empty($page_id_array)) {
            if (is_page($id) && !is_user_logged_in() && in_array($id, $page_id_array)) {
                session_start();
                $_SESSION['redirect_page_id'] = $id;
                // wp_redirect(get_the_permalink(get_page_by_path('user-login')));
                //wp_redirect('http://web.acteonline.org/ACTE/SSO_Login.aspx');
				wp_redirect('https://acte01.isgazurecloud.com/imis/acte/aslogin');                
                die;
            } else if (is_page($id) && is_user_logged_in() && in_array($id, $page_id_array)) {
                $bodemails = get_field('member_emails', 'option');
                $quick_links = get_field('quick_links', 'option');
                $bodpages = array();
                foreach ($quick_links as $quick_link) {
                    $bodpages[] = $quick_link['link'];
                }
                $members = array();
                foreach ($bodemails as $email) {
                    $members[] = $email['email_id'];
                }
                $current_user = wp_get_current_user();
                $role = (array) $current_user->roles;
                $role = $role[0];

                if (!empty($current_user)) {
                    $email = $current_user->data->user_email;
                    if (!in_array($email, $members) && in_array($id, $bodpages) && $role != "administrator") {
                        wp_redirect(home_url('/'));
                        die;
                    }
                }
            }
        }
    }
}

add_action('template_redirect', 'my_page_template_redirect');

function events_filter_func($atts) {
    $terms = get_terms(array('taxonomy' => 'tribe_events_cat', 'hide_empty' => 'false'));
    $event_calander_page_link = get_post_type_archive_link('tribe_events');
    $html = "";
    $html .= '<div class="tabcontainer  top_tab">
				<div class="events-title"></b>ACTE Events</b></div>
				<div class="tab_titles">';
    //$html .= '<li class="events-title"><a data-toggle="tab" href="#"></b>ACTE Events<b></a></li>';

    foreach ($terms as $key => $value) {
        $term_name = $value->name;
        $term_slug = $value->slug;
        if ($key == 0) {
            $class = ' active_tab';
        } else {
            $class = '';
        }
        //$html .= '<li><a data-toggle="tab" href="#'.$term_slug.'" '.$value.'>'.$term_name.'</a></li>';
        $html .= '<div data-fake-id="#tab-id-' . $key . '" class="tab tab_counter_' . $key . $class . '" itemprop="headline">' . $term_name . '</div>';
    }
    $html .= '</div>';
    $html .= '<div class="all-events-link"><a href=' . $event_calander_page_link . '>VIEW All EVENTS</a></div>';
    /* $html .= '<section class="av_tab_section" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">    
      <div data-fake-id="#tab-id-0" class="tab fullsize-tab" itemprop="headline">test</div>
      <div id="tab-id-0-container" class="tab_content">
      <div class="tab_inner_content invers-color" itemprop="text">';
      $html .= '<ul class="ecs-event-list">';
      $html .= '<li>Test </li>';
      $html .= '</ul>';
      //$html .= '</div>';
      $html .= '</div>';
      $html .= '</div>';
      $html .= '</section>'; */
    //$html .= '<div class="tab-content">';
    foreach ($terms as $key1 => $custom_term) {
        $device_name = $custom_term->name;
        $device_slug = $custom_term->slug;

        if ($key1 == 0) {
            $class1 = 'active_tab_content';
        } else {
            $class1 = '';
        }
        $html .= '<section class="av_tab_section event-tab" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">    
						<div data-fake-id="#tab-id-' . $key1 . '" class="tab fullsize-tab" itemprop="headline">' . $device_name . '</div>
						<div id="tab-id-' . $key1 . '-container" class="tab_content $class1">
						<div class="tab_inner_content invers-color" itemprop="text">';



        /* $args = array('post_type' => 'event',
          'posts_per_page' => -1,
          'post_status' => 'publish',
          'tax_query' => array(
          array(
          'taxonomy' => 'event-categories',
          'field' => 'slug',
          'terms' => $custom_term->slug,
          ),
          'order' => 'AESC'
          ),
          );

          $loop = new WP_Query($args); */
        $loop = tribe_get_events(
                array(
                    'eventDisplay' => 'upcoming',
                    'posts_per_page' => 10,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'tribe_events_cat',
                            'field' => 'slug',
                            'terms' => $custom_term->slug
                        )
                    )
                )
        );


        $term_name = $custom_term->name;
        $term_slug = $custom_term->slug;

        $html .= '<ul class="ecs-event-list">';
        foreach ($loop as $value) {
            $post_name = $value->post_title;
            $get_permalink = get_permalink($value->ID);
            $event_website_link = tribe_get_event_meta($value->ID, '_EventURL', true);
            if (!empty(tribe_get_venue($value->ID))) {
                $venue = '<span class="duration time tribe-event-venue">Where:&nbsp' . tribe_get_venue($value->ID) . '</span>';
            } else {
                $venue = " ";
            }
            if (!empty($event_website_link)) {
                $website_link = '<span class="tribe-event-link">Link:&nbsp<a href="' . $event_website_link . '" rel="bookmark">' . $event_website_link . '</a></span>';
            } else {
                $link = "";
            }
            //$html .= '<li >'.$post_name.'<h1>';
            $html .= '<li class="ecs-event national_ecs_category">';

            $html .= '<h3 class="entry-title summary">
								<a href="' . $get_permalink . '" rel="bookmark">' . $post_name . '</a>
							  </h3>';
            $html .= '<span class="duration time"><span class="tribe-event-date-start">When:&nbsp' . tribe_get_start_date($value->ID, true, 'F d, Y @  h:i a') . '</span> - <span class="tribe-event-date-end">' . tribe_get_end_date($value->ID, true, 'F d, Y @  h:i a') . '</span></span>';
            $html .= $venue;
            $html .= $website_link;
            $html .= '</li>';
        }
        $html .= '</ul>';
        //$html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</section>';
    }
    $html .= '</div>';

    return $html;
}

add_shortcode('events_filter', 'events_filter_func');
add_theme_support('add_avia_builder_post_type_option');
// add_theme_support('avia_template_builder_custom_post_type_grid');

/* Enable Avia Builder for Posts */
add_filter('avf_builder_boxes', 'enable_boxes_on_posts');

function enable_boxes_on_posts($boxes) {
    $boxes[] = array('title' => __('Avia Layout Builder', 'avia_framework'), 'id' => 'avia_builder', 'page' => array('page', 'post', 'tribe_events'), 'context' => 'normal', 'expandable' => true);
    $boxes[] = array('title' => __('Layout', 'avia_framework'), 'id' => 'layout', 'page' => array('page', 'post', 'tribe_events'), 'context' => 'side', 'priority' => 'low');
    $boxes[] = array('title' => __('Additional Portfolio Settings', 'avia_framework'), 'id' => 'preview', 'page' => array('page', 'post', 'tribe_events'), 'context' => 'normal', 'priority' => 'high');

    return $boxes;
}

/* Enable Custom Toggle ID For Accordion */
add_theme_support('avia_template_builder_custom_tab_toogle_id');


/* hide view option from custom posts */

function wpc_remove_row_actions($actions) {
    if (get_post_type() === 'technique') { // choose the post type where you want to hide the button  
        unset($actions['view']); // this hides the VIEW button on your edit post screen  
    }
    if (get_post_type() === 'press_hit_post') {
        unset($actions['view']);
    }

    return $actions;
}

add_filter('post_row_actions', 'wpc_remove_row_actions', 10, 1);

function wpc_remove_state_row_actions($actions) {
    if (get_post_type() === 'states') { // choose the post type where you want to hide the button  
        unset($actions['view']); // this hides the VIEW button on your edit post screen  
    }
    return $actions;
}

add_filter('page_row_actions', 'wpc_remove_state_row_actions', 10, 2);

$post_type_array = array('fact_sheets_post', 'home_page_boxes_post', 'press_hit_post', 'press_center_post', 'technique');

function hv_hide_view_button() {
    $current_screen = get_current_screen();
    if ($current_screen->post_type === 'fact_sheets_post' || $current_screen->post_type === 'home_page_boxes_post') {
        echo '<style>#edit-slug-box{display: none;}</style>';
    }

    return;
}

add_action('admin_head', 'hv_hide_view_button');

/**
 * Removes the 'view' link in the admin bar
 *
 */
function hv_remove_view_button_admin_bar() {
    global $wp_admin_bar;
    if (get_post_type() === 'fact_sheets_post' || get_post_type() === 'home_page_boxes_post') {
        $wp_admin_bar->remove_menu('view');
    }
}

add_action('wp_before_admin_bar_render', 'hv_remove_view_button_admin_bar');

/**
 * Removes the 'view' button in the posts list page
 *
 * @param $actions
 */
function hv_remove_view_row_action($actions) {
    if (get_post_type() === 'fact_sheets_post' || get_post_type() === 'home_page_boxes_post')
        unset($actions['view']);
    return $actions;
}

// Applies to non-hierarchical CPT
add_filter('post_row_actions', 'hv_remove_view_row_action', 10, 1);

/*
 * Display a subnavigation for pages that is automatically generated, so the users doesnt need to work with widgets
 */
if (!function_exists('avia_sidebar_menu')) {

    function avia_sidebar_menu($echo = true) {
        $sidebar_menu = "";

        $subNav = avia_get_option('page_nesting_nav');


        $the_id = @get_the_ID();
        $args = array();
        global $post;

        if ($subNav && $subNav != 'disabled' && !empty($the_id) && is_page()) {
            $login_restricted_pages = get_field('restricted_page', 'option');
			$page_id_array = [];

            if (!empty($login_restricted_pages)) {
                foreach ($login_restricted_pages as $pages) {
                    $selected_field = $pages['select_page_or_attachment'];
                    if ($selected_field == 'Page') {
                        $page_link = $pages['add_restricted_pages'];
                        $page_id = $page_link->ID;
                        $page_link = get_permalink($page_id);
                        $page_id_array[] = $page_id;
                    }
                }
            }
            $subNav = false;
            $parent = $post->ID;
            $sidebar_menu = "";
            //$id_array = array();
            if (!empty($post->post_parent)) {
                if (isset($post->ancestors))
                    $ancestors = $post->ancestors;
                $child_pages = get_children($post->ID);

                if (!empty($child_pages)) {

                    foreach ($child_pages as $key => $val) {
                        $id_array[] = $key;
                    }
                }
                if (!isset($ancestors))
                    $ancestors = get_post_ancestors($post->ID);
                $root = count($ancestors) - 1;
                $parent = $ancestors[$root];
            }
            $result = array_intersect($id_array, $page_id_array);
            if (!empty($result) && !is_user_logged_in()) {
                $exclude_page = implode(',', $result);
            } else {
                $exclude_page = " ";
            }

            $args = array('title_li' => '', 'child_of' => $parent, 'echo' => 0, 'sort_column' => 'menu_order, post_title', 'exclude' => $exclude_page);

            //enables user to change query args
            $args = apply_filters('avia_sidebar_menu_args', $args, $post);

            //hide or show child pages in menu - if the class is set to 'widget_nav_hide_child' the child pages will be hidden
            $display_child_pages = apply_filters('avia_sidebar_menu_display_child', 'widget_nav_hide_child', $args, $post);

            $children = wp_list_pages($args);

            if ($children) {
                $default_sidebar = false;
                $sidebar_menu .= "<nav class='widget widget_nav_menu $display_child_pages'><ul class='nested_nav'>";
                $sidebar_menu .= $children;
                $sidebar_menu .= "</ul></nav>";
            }
        }

        $sidebar_menu = apply_filters('avf_sidebar_menu_filter', $sidebar_menu, $args, $post);

        if ($echo == true) {
            echo $sidebar_menu;
        } else {
            return $sidebar_menu;
        }
    }

}

function check_sso_token_status($token) {
    session_start();
    $authenticate_token_url = 'http://iservices.acteonline.org/Authentication.asmx?op=AuthenticateToken';
    $securityPassword = 'A102C162-9A81-4ba5-BDA0-7FDA5DAD6CF6';
    $xmlsrc = '<?xml version="1.0" encoding="utf-8"?>
			<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
			  <soap:Body>
			 <AuthenticateToken xmlns="http://ibridge.isgsolutions.com/Authentication/">
			   <securityPassword>' . $securityPassword . '</securityPassword>
			   <token>' . $token . '</token>
			 </AuthenticateToken>
			  </soap:Body>
			</soap:Envelope>';
    $session = curl_init();
    curl_setopt($session, CURLOPT_URL, $authenticate_token_url);
    curl_setopt($session, CURLOPT_POST, 1);
    curl_setopt($session, CURLOPT_POSTFIELDS, $xmlsrc);
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_HTTPHEADER, array('Accept: text/xml', 'Content-Type: text/xml'));
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);

    $result = curl_exec($session);

    curl_close($session);

    $your_xml_response = str_replace("UTF-16", "UTF-8", $result);
    $clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $your_xml_response);

    $xml = simplexml_load_string($clean_xml);
    $xml_arr = $xml->Body->AuthenticateTokenResponse->AuthenticateTokenResult;
    foreach ($xml_arr as $key => $object) {
        $string = (string) $object[0];
        $string1 = simplexml_load_string($string);
        if (count($string1->Errors) > 0) {
            if (isset($_SESSION['user_token'])) {
                unset($_SESSION['user_token']);
                wp_logout();
            }
        }
    }
}

add_filter('avia_load_shortcodes', 'avia_include_shortcode_template', 15, 1);

function avia_include_shortcode_template($paths) {
    $template_url = get_stylesheet_directory();
    array_unshift($paths, $template_url . '/avia-shortcodes/');

    return $paths;
}


if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'ACTEHQ Settings',
		'menu_title'	=> 'ACTEHQ Settings',
		'menu_slug' 	=> 'acte-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

    acf_add_options_page(array(
        'page_title'    => __('Michigan Layout Settings'),
        'menu_title'    => __('Michigan Layout Settings'),
        'menu_slug'     => 'michigan-layout-settings',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));    
	
}

add_menu_page('ACTEHQ Reports', 'ACTEHQ Reports', 'edit_posts', 'acte-reports', 'actereports', '', 90);

//set builder mode to debug
add_action( 'avia_builder_mode', "builder_set_debug" );
function builder_set_debug () {
    return "debug";
}



add_shortcode("homecarousel", "homecarousel");
function homecarousel($atts){
	global $post;
	$args = array(
	    'post_type' => 'home_page_boxes_post',
	    'posts_per_page' => -1
	);
	$the_query = new WP_Query( $args );
	$contentHTML = '';
	if ( $the_query->have_posts() ) {
	    while ( $the_query->have_posts() ) {
	        $the_query->the_post();
            $permalink = get_field("external_link");
            if($permalink=="")
                $permalink = get_permalink();
	        $fullimage = get_the_post_thumbnail_url( $post->ID, 'full' );

            $taxonomies  = get_object_taxonomies(get_post_type($post->ID));
            $cats = '';
            $excluded_taxonomies = array_merge( get_taxonomies( array( 'public' => false ) ), array('post_tag','post_format') );
			$excluded_taxonomies = apply_filters('avf_exclude_taxonomies', $excluded_taxonomies, get_post_type($post->ID), $post->ID);

            if(!empty($taxonomies))
            {
                foreach($taxonomies as $taxonomy)
                {
                    if(!in_array($taxonomy, $excluded_taxonomies))
                    {
                        $cats .= get_the_term_list($the_id, $taxonomy, '', ', ','').' ';
                    }
                }
            }

		    $contentHTML .= '
		    					<div class="item">
								<article class="post-entry post post-entry-type-standard post-entry-'.$post->ID.' hentry box-category-home-page-box" itemscope="itemscope" itemtype="https://schema.org/CreativeWork">
								   <div class="big-preview single-big">
								      <a href="'.$permalink.'" title="'.$post->post_title.'" target="_blank" data-feathr-click-track="true"><img src="'.$fullimage.'" class="wp-image-47599 attachment-entry_without_sidebar size-entry_without_sidebar wp-post-image" alt="'.$post->post_title.'"><span class="image-overlay-inside"></span></span></a>
								      <header class="entry-content-header">
								         <h2 class="post-title entry-title " itemprop="headline">
								         	<a href="'.$permalink.'" rel="bookmark" title="Permanent Link:  " data-feathr-click-track="true"><span class="post-format-icon minor-meta"></span></a>
								         </h2>
								         <span class="post-meta-infos"><time class="date-container minor-meta updated">'.get_the_time(get_option('date_format')).'</time><span class="text-sep text-sep-date">/</span><span class="blog-categories minor-meta">'.$cats.'</span><span class="text-sep text-sep-cat">/</span><span class="blog-author minor-meta">by <span class="entry-author-link"><span class="vcard author"><span class="fn">'.get_the_author_posts_link().'</span></span></span></span></span>
								      </header>
								   </div>
								   <div class="blog-meta"></div>
								   <div class="entry-content-wrapper clearfix standard-content" style="height: 154.594px;">
								      <div class="entry-content" itemprop="text">
								         '.wpautop($post->post_content).'
								         <div class="read-more-link"><a href="'.$permalink.'" class="more-link" target="_blank" data-feathr-click-track="true">Learn More<span class="more-link-arrow"></span></a></div>
								      </div>
								      <footer class="entry-footer"></footer>
								      <div class="post_delimiter"></div>
								   </div>
								   <div class="post_author_timeline"></div>
								</article>	
								</div>
		    				';
	    }
	    echo '</ul>';
	} else {
	    // no posts found
	}
	/* Restore original Post Data */
	wp_reset_postdata();
	$contentHTML = '
						<div class="av-alb-blogpostsss template-blog post-slider" itemscope="itemscope" itemtype="https://schema.org/Blog">
							'.$contentHTML.'
						</div>
						<style type="text/css">
							.owl-nav>div {
							    opacity: 1!important;
    							top: 40%;
							}			
							#top .post-slider .template-blog .owl-nav.disabled{
								display:block!important;
							}	
							#top .post-slider .template-blog {
							    position: relative;
							}							
							#top .post-slider .template-blog .owl-nav {
							    height: 100%;
                                top: 0;
							}							
							#top .post-slider .template-blog .owl-nav .owl-prev,
							#top .post-slider .template-blog .owl-nav .owl-next {
							    background: transparent;
							    height: auto;
    							display: block!important;
                                border: 0;
                                outline: none;
							}		
                            .owl-nav>button {
                                position: absolute;
                                opacity: 1!important;
                                top: 40%;
                            }                            
                            #top .post-slider .owl-dots{
                                display:none!important;
                            }		
							#top .post-slider .template-blog .owl-nav .owl-prev:before,
							#top .post-slider .template-blog .owl-nav .owl-next:before {
							    content: "\f104";
							    background: transparent;
							    background-repeat: no-repeat;
							    background-position: 0px center;
							    font-size: 50px;
							    width: 20px;
							    display: inline-block;
							    vertical-align: middle;
							    margin: 0 auto;
							    height: auto;
							    font-family: "FontAwesome";
							    color: #0e69b0;
							}	
							#top .post-slider .template-blog .owl-nav .owl-next:before{
							    content: "\f105";
							}			
							@media (max-width: 767px){
                                .owl-item {
                                    background: transparent!important;
                                }                                
								#top .post-slider .template-blog .owl-nav .owl-prev {
    								left: -10px!important;
    							}
								#top .post-slider .template-blog .owl-nav .owl-next {
    								right: -5px!important;
    							}
							}					
						</style>
				   ';
	return $contentHTML;
}

// imis_sso_login
include_once("includes/imis_sso_login.php");
