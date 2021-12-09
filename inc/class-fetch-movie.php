<?php


if ( ! class_exists('ddFetchMovie') ) {

    class ddFetchMovie
    {

        private $base_domain = 'https://api.themoviedb.org/3';
        private $api_key = 'bd8ebe0bbf2b360dbc45f779b43d174d';
        private $movies = [];

        public function __construct()
        {
            include_once( ABSPATH . 'wp-admin/includes/admin.php' );

            add_action( 'wp_ajax_get_add_movies', [$this, 'add_movies'] );
            add_action( 'wp_ajax_nopriv_add_movies', [$this, 'add_movies'] );
            // add_action( 'admin_init', [$this, 'add_movies'] );
        }
         
        // add movies
        // - get_movies_from_api (fetch movie)
        // - insert movie
        // ---- set movie main image
        // ---- add custom fields data
        // ---- go to the next api page

        public function add_movies()
        {
            $current_page = ( ! empty($_POST['current_page']) ) ? $_POST['current_page'] : 1;
            $this->get_movies_from_api($current_page);

            $response = wp_remote_get( $url, array(
                'timeout' => 30,
                'sslverify' => false,
            ) );
            if ( is_wp_error( $response ) ){
                echo $response->get_error_message();
            }

            // return $response;

            else if ( wp_remote_retrieve_response_code( $response ) === 200 ){
                $body = wp_remote_retrieve_body( $response );
                $movies = json_decode( $body );

                if ( ! is_object( $movies ) ||  empty( $movies ) ) {
                    return false;
                }

                // 
                print_r( $movies );
                wp_die();

                // insert
                foreach ( $movies->results as $key => $movie ) {
                    // get genres for current movie

                    $genres_temp = $this->get_movie_genres_by_id( $movie->id );
                    $movie->posters = $this->get_movie_images_by_id( $movie->id );
                    foreach ( $genres_temp as $genre ) {
                        $movie->genres[] = $genre->name;
                    }


                    // insert
                    $movie_slug = sanitize_title( $movie->title );
                    $existing_movie = get_page_by_path( $movie_slug, 'OBJECT', 'movies' );

                    if ( $existing_movie == null ) {
                        $inserted_movie = wp_insert_post( array(
                            'post_name' => $movie_slug,
                            'post_title' => $movie_slug,
                            'post_content' => $movie->overview,
                            'post_type' => 'movies',
                            'post_status' => 'publish' 
                        ) );

                        // set terms
                        wp_set_object_terms($inserted_movie, $movie->genres, 'genres', true);

                        // set image
                        $poster_base_url = 'https://www.themoviedb.org/t/p/w600_and_h900_bestv2';
                        $poster_url = $poster_base_url . $movie->poster_path;
            
                        $file = array();
                        $file['name'] = $poster_url;
                        $file['tmp_name'] = download_url($poster_url);
                        
                        if (is_wp_error($file['tmp_name'])) {
                            @unlink($file['tmp_name']);
                        } else {
                            $attachmentId = media_handle_sideload($file, $inserted_movie);
                             
                            if ( is_wp_error($attachmentId) ) {
                                @unlink($file['tmp_name']);
                            } else {                
                                $setted_post_thumb_id = set_post_thumbnail( $inserted_movie, $attachmentId );
                            }
                        }
                                 
                        if ( is_wp_error($attachmentId) ) continue;
            
                        // set custom fields
                        update_field( "id", $movie->id, $inserted_movie );
                        update_field( "release_date", $movie->release_date, $inserted_movie );
                        update_field( "vote_average", $movie->vote_average, $inserted_movie );
                        update_field( "original_language", $movie->original_language, $inserted_movie );
                        // update_field( "id", $movie['id'], $inserted_movie );

                        if ( $movie->posters ) {
                            $field_key = "posters";
                            $value = array();
                            foreach ($movie->posters as $item) {

                                $repeater_file = array();
                                $repeater_file['name'] = $poster_base_url . $item->file_path;
                                $repeater_file['tmp_name'] = download_url($poster_base_url . $item->file_path); 
                                if (is_wp_error($repeater_file['tmp_name'])) {
                                    @unlink($repeater_file['tmp_name']);
                                } else {
                                    $repeater_attachmentId = media_handle_sideload($repeater_file, $inserted_movie);
                                     
                                    if ( is_wp_error($repeater_attachmentId) ) {
                                        @unlink($repeater_file['tmp_name']);
                                    } else {                
                                        $value[] = array(
                                            'image' => $repeater_attachmentId,
                                        );

                                    }
                                }
                            } 
                            update_field($field_key, $value, $inserted_movie);
                        } 

                        if ( is_wp_error( $inserted_movie ) ) {
                            continue;
                        }

                    }
                    // end insert if
                }

                // go to the next page
                if ( $current_page > 2 ) {
                    return;
                }

                $current_page =  $current_page + 1;
                wp_remote_post( admin_url('admin-ajax.php?action=add_movies'), array(
                    'blocking' => false,
                    'sslverify' => false,
                    'body' => array(
                        'current_page' => $current_page,
                    ),
                ) );

            }
        }

        /**
         * Get movie genres by id
         * get @id (int)
         * return @array 
         **/ 
        private function get_movie_genres_by_id( $movie_id )
        {
            $url = "{$this->base_domain}/movie/{$movie_id}?api_key={$this->api_key}&language=en-US";
            $response = wp_remote_get( $url );

            if ( is_wp_error( $response ) ) {
                echo $response->get_error_message();
            } 
            else if ( wp_remote_retrieve_response_code( $response ) ) {
                $movie_details = wp_remote_retrieve_body( $response );
                $movie_details = json_decode( $movie_details );

                if ( ! is_object( $movie_details ) || empty( $movie_details ) ) {
                    return false;
                }
            }

            return $movie_details->genres;
        }

        /**
         * Get movie images by id
         * get @id (int)
         * return @array 
         **/ 
        public function get_movie_images_by_id( $movie_id )
        {
            $url = "{$this->base_domain}/movie/{$movie_id}/images?api_key={$this->api_key}&language=en-US&include_image_language=en,null";
            $response = wp_remote_get( $url );

            if ( is_wp_error( $response ) ) {
                echo $response->get_error_message();
            } 
            else if ( wp_remote_retrieve_response_code( $response ) ) {
                $movie_details = wp_remote_retrieve_body( $response );
                $movie_details = json_decode( $movie_details );

                if ( ! is_object( $movie_details ) || empty( $movie_details ) ) {
                    return false;
                }
            }

            // get first 4 elements (exclude first)
            $posters = array_slice($movie_details->backdrops, 1, 4, true);

            return $posters;
        }

        // ========================================================================
        public function insertMovies()
        {
           

        }

    }

    new ddFetchMovie();

}
// $ddFetchMovie->insertMovies();