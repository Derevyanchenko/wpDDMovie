<?php 

if ( ! class_exists( 'insertMovie' ) ) {
    
    class insertMovie
    {
        public function __construct()
        {
            // test
        }

        public function insertMovies()
        {
            $movieArray = new ddFetchMovie()->getPopularMovies();
            echo '<h1>Title: </h1>';
            dd($movieArray, true);
        }

    }

}

new insertMovie()->insertMovies();