<?php

add_action('wp_enqueue_scripts', 'own_style');

function own_style () {
    wp_enqueue_style('style',  THEME_URL . '/dist/app.css');
}