<?php

// =============== RENDER FUNCTION ==================
function render($output) {
    if ( ! empty( $output ) ) :
        foreach( $output['results'] as $movie) : 

        $img_path = 'https://www.themoviedb.org/t/p/w600_and_h900_bestv2/';
    ?>
    <div class="movie">
        <div class="movie__image">
            <img src="<?php echo $img_path . $movie['poster_path']; ?>" alt="poster">
        </div>
        <div class="movie__content">
            <h3 class="movie__title">Title: <?php echo $movie['original_title']; ?></h3>
            <p class="movie__lang">Rating: <?php echo $movie['vote_average']; ?></p>
            <p class="movie__year">Release date: <?php echo $movie['release_date']; ?></p>
            <p class="movie__text">Description: <?php echo $movie['overview']; ?></p>
        </div>
    </div>
    <br>
    <hr>
    <br>
    <?php endforeach; endif; 
}

// render($output);
