<?php

function add_menus(){
    add_theme_support('menus');

    register_nav_menu('footer', 'Menu footer');
    
}

add_action('init', 'add_menus');