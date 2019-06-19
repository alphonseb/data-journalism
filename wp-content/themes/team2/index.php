<?php

get_header();

echo "<div id=app>";

if ( have_posts()) {
    while (have_posts()){
        the_post();
    }
}

echo "</div>";

get_footer();