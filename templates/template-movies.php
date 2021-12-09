<?php
/*
Template Name: Template Movies 
*/

// get_header();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <?php wp_head(); ?>
</head>
<body class="font-sans bg-gray-900 text-white">
    <!-- body -->

    <div id="ddmovie-app"></div>

    <!-- content -->

    <footer class="border border-t border-gray-800">
        <div class="container mx-auto text-sm px-4 py-6">
            Powered by <a href="https://www.themoviedb.org/documentation/api" class="underline hover:text-gray-300">TMDb API</a>
        </div>
    </footer>

    <!-- // body end -->
    <?php wp_footer(); ?>
</body>
</html>

<?php 
// get_footer();