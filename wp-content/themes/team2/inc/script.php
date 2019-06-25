<?php

add_action('wp_enqueue_scripts', 'own_script');

function own_script () {
    wp_enqueue_script('mapsAPI', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyA8PzSmtvBRCQJrhGYtXnTXLa3NJ6FZFpw&libraries=places', array(), '1.0.0', true);
    wp_enqueue_script('script', THEME_URL.'/dist/app.js', array('mapsAPI'), '1.0.0', true);
    
}