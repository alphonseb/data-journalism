<?php
    $title = get_the_title();
    
    $excerpt = get_the_excerpt();
    
    $home_url = get_home_url();
    
    // $thumbnail_url = get_the_post_thumbnail_url();
    
    $vignettes = [];
    if (have_rows('vignettes')) {
        while (have_rows('vignettes')) {
            the_row();
            $vignettes[]=[
                'type' => get_row_layout(),
                'front' => get_sub_field('front'),
                'back' => get_sub_field('back')
            ];
        }
    }
    
    $footerMenu = [];

foreach (wp_get_nav_menu_items('footer') as $key => $value) {
    $footerMenu[] = [
        "title" => $value->title,
        "url" => $value->url
    ];
}
?>

<?php get_header() ?> 

<div id="app">
    <Single 
    title="<?= $title ?>"
    excerpt="<?= $excerpt ?>"
    thumbnail_url="<?= $thumbnail_url ?>"
    vignettes_string="<?= htmlspecialchars(json_encode($vignettes), ENT_QUOTES, 'UTF-8') ?>"
    footer-menu="<?= htmlspecialchars(json_encode($footerMenu), ENT_QUOTES, 'UTF-8') ?>"
    home-url="<?= $home_url ?>"
    >
    </Single>
</div>

<?php get_footer() ?> 