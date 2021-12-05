<?php 
/*
Plugin Name: WP ddMovie
Plugin URI: #
Description: Movie plugin grabbing all movies and data from themoviedb.org API, insert into Custom Post Type "Movies" and create pages with all movies, single movie and sorting, pagination.
Version: 1.0
Author: Danil Derevyanchenko
Author URI: #
Licence: GPLv2 or later
Text Domain: ddmovie
*/ 

if( ! defined('ABSPATH') ) {
    die;
}

/***
 * План по разработке 
 * 
 * 1. Класс получения листа фильмов
 * 2. запись фильмов в Кастом пост тайп
 * 3. Обновление всех данных в custom fields
 * 4. render shortcode 
 * 5. add front (html, css, js)
 * . render frontend SPA - react
 * ==========================
 * 
 * для получения всей инфы о фильме, а не только базовой можно сделать так:
 * 
 * - функция - получаем 10 фильмов для теста и записываем id вставленного поста в массив, потом в конце этой функции мы делаем функцию, которая проходит циклом по массиву вставленных постов и отправляет по каждому get запрос в api на получение фулл данных о фильме и обновляет остальные кастомные поля.
 */ 

define('DDMOVIE_PATH', plugin_dir_path(__FILE__));

// Define path and URL to the ACF plugin.
define( 'MY_ACF_PATH', DDMOVIE_PATH . '/vendor/acf/' );
define( 'MY_ACF_URL', DDMOVIE_PATH . '/vendor/acf/' );

// Include the ACF plugin.
if ( ! class_exists( 'ACF' ) ) {
    require_once DDMOVIE_PATH . 'vendor/acf/acf.php';
}


require DDMOVIE_PATH . 'inc/helpers.php';

if ( ! class_exists('ddMovieCpt') ) {
    require_once DDMOVIE_PATH . 'inc/class-ddmovie-cpt.php';
}

if ( ! class_exists('ddFetchMovie') ) {
    require DDMOVIE_PATH . 'inc/class-fetch-movie.php';
}

// // if ( ! class_exists('insertMovie') ) {
// //     require DDMOVIE_PATH . 'inc/class-insert-movie.php';
// // }


if ( ! class_exists('ddMovie') ) 
{

    class ddMovie 
    {
        public function __construct() 
        {
            // add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
            // add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

            // add_action( 'init', [$this, 'ddWishlist_create_settings_pages'] );
            // add_action()

            // add_action( 'init', [$this, 'run'] );
        }

        public function run()
        {
            echo '<h1>Run dd21321 Movie!</h1>';
        }

        /**
         * Create settings page for 'Wishlist' 
         */
        public function ddWishlist_create_settings_pages()
        {
            if( function_exists('acf_add_options_page') ) {
        
                acf_add_options_page(array(
                    'page_title' 	=> 'ddWishlist Settings',
                    'menu_title'	=> 'Wishlist Settings',
                    'menu_slug' 	=> 'theme-general-settings',
                    'capability'	=> 'edit_posts',
                    'redirect'		=> false
                ));
                
                // acf_add_options_sub_page(array(
                //     'page_title' 	=> 'Theme Header Settings',
                //     'menu_title'	=> 'Header',
                //     'parent_slug'	=> 'theme-general-settings',
                // ));
                
                // acf_add_options_sub_page(array(
                //     'page_title' 	=> 'Theme Footer Settings',
                //     'menu_title'	=> 'Footer',
                //     'parent_slug'	=> 'theme-general-settings',
                // ));
                
            }
        }

        /**
         * enqueue frontend styles method
         */
        public function enqueue_styles()
        {
            wp_enqueue_style('ddWishlist_main_style', plugins_url( '/assets/css/main.css', __FILE__ ));
        }

        /**
         * enqueue frontend scripts method
         */
        public function enqueue_scripts()
        {
            wp_register_script('ddwishlist_main_script', plugins_url( '/assets/js/main.js', __FILE__ ), array('jquery'), time() );

            wp_localize_script('ddwishlist_main_script', 'ddwishlist_ajax', array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('_wpnonce'),
                'title' => esc_html__('ddWishlist test title', 'ddWishlist'),
            ));

            wp_enqueue_script( 'ddwishlist_main_script' );
        }


        /**
         * activation hook
         */
        static function activation() 
        {
            flush_rewrite_rules();
        }

        /**
         * deactivation hook
         */
        static function deactivation()
        {
            flush_rewrite_rules();
        }

    }

    $ddMovie = new ddMovie();

    register_activation_hook( __FILE__, array( $ddMovie, 'activation' ) );
    register_deactivation_hook( __FILE__, array( $ddMovie, 'deactivation' ) );

}




// ===========================================================
// OLD
// add_action( 'wp_ajax_get_breweries_from_api', 'get_breweries_from_api' );
// add_action( 'wp_ajax_nopriv_get_breweries_from_api', 'get_breweries_from_api' );

function get_breweries_from_api()
{
    // $file = DDMOVIE_PATH . '/report.txt';

    $breweries = [];
    $current_page = ( ! empty($_POST['current_page']) ) ? $_POST['current_page'] : 1;


    $url = "https://api.openbrewerydb.org/breweries?page={$current_page}&per_page=50";
    $response = wp_remote_get( $url );
    
    if ( is_wp_error( $response ) ){
        echo $response->get_error_message();
    }
    elseif ( wp_remote_retrieve_response_code( $response ) === 200 ){
        $body = wp_remote_retrieve_body( $response );

        $body = json_decode( $body, true );
        
        if ( ! is_array( $body ) ||  empty( $body ) ) {
            return false;
        }

        // create custom post type post via api
        $breweries[] = $body;

        foreach ( $breweries[0] as $brewery ) {
            $brewery_slug = sanitize_title( $brewery->name . '-' . $brewery->id );

            // check if post exists
            $exisiting_brewery = get_page_by_path( $brewery_slug, 'OBJECT', 'brewery' );

            if ( $exisiting_brewery == null ) {
                $inserted_brewery = wp_insert_post( array(
                    'post_name' => $brewery_slug,
                    'post_title' => $brewery_slug,
                    'post_type' => 'brewery',
                    'post_status' => 'publish' 
                ) );
    
                if ( is_wp_error( $inserted_brewery ) ) {
                    continue;
                }
                // $fillable = [
                //     'field_key43243' => 'name',
                //     'field_key43243' => 'brewery_type',
                //     '...'
                // ];
    
                // foreach ( $fillable as $key => $name ) {
                //     update_field( $key, $brewery-$>name, $inserted_brewery );
                // }
            } else {
                // check if the item (brewery) has new updates in API

                $exisiting_brewery_id = $exisiting_brewery_id->ID;
                $exisiting_brewery_timestamp = get_field('updated_at', $exisiting_brewery_id);

                if ( $brewery->updated_at >= $exisiting_brewery_timestamp ) {
                    // если дата в апи новее, то обновляем опять все поля
                    // цикл который был выше
                    update_field( $key, $brewery->$name, $exisiting_brewery_id );
                }
            }

        }


        // changing 'current_page' value via send request in our site (for getting all posts, not only first 50) = (recursion)
        $current_page = $_POST['current_page'] + 1;
        wp_remote_post( admin_url('admin-ajax.php?action=get_breweries_from_api'), array(
            'blocking' => false,
            'sslverify' => false,
            'body' => array(
                'current_page' => $current_page,
            ),
        ) );

        // echo '<pre>';
        // print_r( $breweries );
        // echo '</pre>';
    }
}

// wp_die();

// get_breweries_from_api();