<?php 

// =============== DEBUG ==================
function pr($variable, $dieOrNot = false) {
    echo '<pre>';
    print_r($variable);
    echo '</pre>';

    $dieOrNot ? die() : ''; 
}

function vd($variable, $dieOrNot = false) {
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';

    $dieOrNot ? die() : ''; 
}