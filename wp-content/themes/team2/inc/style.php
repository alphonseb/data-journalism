<?php

add_action('wp_enqueue_scripts', 'own_style');

function own_style () {
    wp_enqueue_style('leaflet', 'https://unpkg.com/leaflet@1.5.1/dist/leaflet.css');
    wp_enqueue_style('Montserrat', 'https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap');
    wp_enqueue_style('style',  THEME_URL . '/dist/app.css', array('Montserrat'));
}