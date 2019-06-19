<?php

add_action('wp_enqueue_scripts', 'own_script');

function own_script () {
    wp_enqueue_script('script', THEME_URL.'/dist/app.js', array(), '1.0.0', true);
}