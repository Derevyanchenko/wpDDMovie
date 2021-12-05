<?php 

if ( ! class_exists('ddMovieCpt') ) {

    class ddMovieCpt
    {

        public function __construct() {
            add_action('init', [$this, 'custom_post_type']);
            add_action('init', [$this, 'genres_taxonomy']);
        }

        public function custom_post_type() {
            register_post_type('movies', 
            array(
                'public' => true,
                'has_archive' => true,
                'rewrite' => array('slug' => 'movies'),
                'label' => 'Movies',
                'supports' => array('title', 'editor', 'thumbnail'),
                'show_in_rest' => true
            ));
        }

        // хук для регистрации
        function genres_taxonomy(){

            // список параметров: wp-kama.ru/function/get_taxonomy_labels
            register_taxonomy( 'genres', [ 'movies' ], [
                'label'                 => 'Genres', // определяется параметром $labels->name
                'labels'                => [
                    'name'              => 'Genres',
                    'singular_name'     => 'Genre',
                    'search_items'      => 'Search Genres',
                    'all_items'         => 'All Genres',
                    'view_item '        => 'View Genre',
                    'parent_item'       => 'Parent Genre',
                    'parent_item_colon' => 'Parent Genre:',
                    'edit_item'         => 'Edit Genre',
                    'update_item'       => 'Update Genre',
                    'add_new_item'      => 'Add New Genre',
                    'new_item_name'     => 'New Genre Name',
                    'menu_name'         => 'Genres',
                    'back_to_items'     => '← Back to Genre',
                ],
                'description'           => '', // описание таксономии
                'public'                => true,
                // 'publicly_queryable'    => null, // равен аргументу public
                // 'show_in_nav_menus'     => true, // равен аргументу public
                // 'show_ui'               => true, // равен аргументу public
                // 'show_in_menu'          => true, // равен аргументу show_ui
                // 'show_tagcloud'         => true, // равен аргументу show_ui
                // 'show_in_quick_edit'    => null, // равен аргументу show_ui
                'hierarchical'          => false,

                'rewrite'               => true,
                //'query_var'             => $taxonomy, // название параметра запроса
                'capabilities'          => array(),
                'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
                'show_admin_column'     => false, // авто-создание колонки таксы в таблице ассоциированного типа записи. (с версии 3.5)
                'show_in_rest'          => true, // добавить в REST API
                'rest_base'             => null, // $taxonomy
                // '_builtin'              => false,
                //'update_count_callback' => '_update_post_term_count',
            ] );
        }
    }

    

    $ddMovieCpt = new ddMovieCpt();

}