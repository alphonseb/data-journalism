<?php

function add_menus(){
    add_theme_support('menus');

    register_nav_menu('header', 'Menu header');
    
}

add_action('init', 'add_menus');