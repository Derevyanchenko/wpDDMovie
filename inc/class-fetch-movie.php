<?php


if ( ! class_exists('ddFetchMovie') ) {

    class ddFetchMovie
    {

        private $base_domain = 'https://api.themoviedb.org/3';
        private $api_key = 'bd8ebe0bbf2b360dbc45f779b43d174d';
        private $movies = [];

        public function __construct()
        {
            // add_action( 'init', [$this, 'add_movies'] );
            // add_action( 'init', [$this, 'add_taxonomies'] );
        }
        

        public function add_taxonomies()
        {
            include_once( ABSPATH . 'wp-admin/includes/admin.php' );


            $post_id_temp = 1298;
            $poster_base_url = 'https://www.themoviedb.org/t/p/w600_and_h900_bestv2';
            $poster_url = "{$poster_base_url}/rjkmN1dniUHVYAtwuV3Tji7FsDO.jpg";

            $file = array();
            $file['name'] = $poster_url;
            $file['tmp_name'] = download_url($poster_url);
            
            if (is_wp_error($file['tmp_name'])) {
                @unlink($file['tmp_name']);
            } else {
                $attachmentId = media_handle_sideload($file, $post_id);
                 
                if ( is_wp_error($attachmentId) ) {
                    @unlink($file['tmp_name']);
                } else {                
                    $setted_post_thumb_id = set_post_thumbnail( $post_id_temp, $attachmentId );
                }
            }
                     
            // if ( is_wp_error($attachmentId) ) continue;

            vd( $setted_post_thumb_id );
        }

        // add_movies 
        // insert_movies
        // fetch_movies

        public function add_movies()
        {
            $url = "{$this->base_domain}/movie/popular?api_key={$this->api_key}&language=en-US&page=1";

            $response = wp_remote_get( $url, array(
                'timeout' => 30,
                'sslverify' => false,
            ) );

            if ( is_wp_error( $response ) ){
                echo $response->get_error_message();
            }

            else if ( wp_remote_retrieve_response_code( $response ) === 200 ){
                $body = wp_remote_retrieve_body( $response );
                $movies = json_decode( $body );

                if ( ! is_object( $movies ) ||  empty( $movies ) ) {
                    return false;
                }

                // insert
                foreach ( $movies->results as $key => $movie ) {
                    // pr( $movie );
                    // id
                    // original_language
                    // title
                    // overview
                    // release_date
                    // poster_path
                    // vote_average

                    // get genres for current movie

                    $genres_temp = $this->get_movie_genres_by_id( $movie->id );
                    // $movie->posters = $this->get_movie_images_by_id( $movie->id );
                    foreach ( $genres_temp as $genre ) {
                        $movie->genres[] = $genre->name;
                    }

                    pr( $movie );
                    wp_die();
                    
                    // load image
                    
                    // $poster_base_url = 'https://www.themoviedb.org/t/p/w600_and_h900_bestv2';
                    // $poster_path = "{$poster_base_url}/rjkmN1dniUHVYAtwuV3Tji7FsDO.jpg";

                    // insert
                    $movie_slug = sanitize_title( $movie->title );
                    $existing_movie = get_page_by_path( $movie_slug, 'OBJECT', 'movies' );

                    if ( $existing_movie == null ) {
                        $inserted_movie = wp_insert_post( array(
                            'post_name' => $movie_slug,
                            'post_title' => $movie_slug,
                            'post_type' => 'movies',
                            'post_status' => 'publish' 
                        ) );

                        wp_set_object_terms($inserted_movie, $movie->genres, 'genres', true);

            
                        if ( is_wp_error( $inserted_movie ) ) {
                            continue;
                        }

                    }
                }

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

            return $movie_details->posters;
        }

        // ========================================================================
        public function insertMovies()
        {
           

        }

    }

    new ddFetchMovie();

}
// $ddFetchMovie->insertMovies();