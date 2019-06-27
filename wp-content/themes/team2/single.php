<?php
$title = get_the_title();

$excerpt = get_the_excerpt();

$home_url = get_home_url();

$transport_type = get_the_terms(get_the_ID(), 'category')[0]->slug;


$vignettes = [];
if (have_rows('vignettes')) {
    while (have_rows('vignettes')){
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
    transport-type="<?= $transport_type ?>"
    vignettes_string="<?= htmlspecialchars(json_encode($vignettes), ENT_QUOTES, 'UTF-8') ?>"
    footer-menu="<?= htmlspecialchars(json_encode($footerMenu), ENT_QUOTES, 'UTF-8') ?>"
    home-url="<?= $home_url ?>">
    </Single>
</div>

<?php get_footer() ?>